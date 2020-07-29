<?php
namespace App\Betting;

use App\Betting\Bet365\Model\Event;
use App\Betting\Bet365\Parser\AmericanFootball;
use App\Betting\Bet365\Parser\Baseball;
use App\Betting\Bet365\Parser\BasketBall;
use App\Betting\Bet365\Parser\IceHockey;
use App\Betting\Bet365\Parser\NoOdds;
use App\Betting\Bet365\Parser\Soccer;
use App\Betting\Bet365\Parser\Tennis;
use App\Models\ApiEvent;
use Decimal\Decimal;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

class Bets365 implements BettingProvider
{
    private const RESULTS_CACHE_TTL = 5 * 60;
    private const PREMATCH_CACHE_TTL = 2 * 60;

    const PROVIDER_NAME = "bet365";

    private static array $parsers = [
        1  => Soccer::class,
        12 => AmericanFootball::class,
        13 => Tennis::class,
        16 => Baseball::class,
        17 => IceHockey::class,
        18 => BasketBall::class,
    ];

    private Bets365API $api;
    private CacheInterface $cache;
    private LoggerInterface $logger;
    private EntityManager $entityManager;

    public function __construct(CacheInterface $cache, Bets365API $api, LoggerInterface $logger, EntityManager $entityManager)
    {
        $this->api = $api;
        $this->cache = $cache;
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }

    public function getEvents(int $page): Pagination
    {
        $perPage = 50;

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('e, l, s, h, a')
            ->from(Event::class, 'e')
            ->join('e.home', 'h')
            ->join('e.away', 'a')
            ->join('e.league', 'l')
            ->join('l.sport', 's')
            ->orderBy('e.time')
            ->setMaxResults($perPage)
            ->setFirstResult($perPage * ($page-1));

        $paginator = new Paginator($qb);
        $results = [];

        foreach ($paginator as $event) {
            $results[] = $event->toSportEvent();
        }

        return new Pagination($results, $paginator->count(), $perPage);
    }

    public function getOdds(): array
    {
        /** @var \App\Domain\ApiEvent[]|Collection $apiEventDict */
        $qb = $this->entityManager->createQueryBuilder();
        $apiEventDict = $qb->select('a')
            ->from(\App\Domain\ApiEvent::class, 'a')
            ->where($qb->expr()->eq('a.provider', '?1'))
            ->andWhere($qb->expr()->eq('a.timeStatus', '?2'))
            ->indexBy('a', 'apiId')
            ->getQuery()
            ->execute([1 => static::PROVIDER_NAME, 2 => TimeStatus::NOT_STARTED()->getValue()]);

        $preMatchOdds = collect();
        $eventsIdsToRequest = collect();

        $cacheKeys = $apiEventDict->map(
            fn(\App\Domain\ApiEvent $apiEvent) => $this->createPreMatchKey($apiEvent->getApiId()),
        );
        $cachePreMatchOdds = collect($this->cache->getMultiple($cacheKeys));

        foreach ($apiEventDict as $apiEvent) {
            $cacheItem = $cachePreMatchOdds->get($this->createPreMatchKey($apiEvent->getApiId()));
            if ($cacheItem) {
                $preMatchOdds->push($cacheItem);
            } else {
                $eventsIdsToRequest->push($apiEvent->getApiId());
            }
        }

        // Call API for data in 10-elements chunks
        foreach ($eventsIdsToRequest->chunk(10) as $keys) {
            $results = collect($this->api->getPreMatchOdds($keys->values()));
            $this->cache->setMultiple(
                $results->mapWithKeys(
                    fn(array $event) => [$this->createPreMatchKey($event['FI']) => $event],
                ),
                static::PREMATCH_CACHE_TTL,
            );
            $preMatchOdds->push(...$results);
        }

        // Map API items into SportEventOdds
        $odds = collect($preMatchOdds)
            ->map(function (array $event) use ($apiEventDict) {
                $eventId = $event["FI"];
                /** @var \App\Domain\ApiEvent $apiEvent */
                $apiEvent = $apiEventDict[$eventId];

                if (!isset(self::$parsers[$apiEvent->getSportId()])) {
                    return new SportEventOdd($eventId);
                }

                $parser = new self::$parsers[$apiEvent->getSportId()];

                $odds = $parser->parseMainLines($event, $apiEvent->getTeamHome(), $apiEvent->getTeamAway());
                $apiEvent->updateOdds($odds);
                $errors = $parser->getErrors();
                return $odds;
            })
            ->all();

        $this->entityManager->flush();

        return $odds;
    }

    public function getResults(): array
    {
        $apiEvents = ApiEvent::notFinished()
            ->started()
            ->where("provider", static::PROVIDER_NAME)
            ->get();

        $eventsResults = collect();
        $eventsIdsToRequest = collect();

        $cacheKeys = $apiEvents->map(
            fn(ApiEvent $apiEvent) => $this->createResultKey($apiEvent->api_id),
        );
        $cacheEventsResults = collect($this->cache->getMultiple($cacheKeys));

        foreach ($apiEvents as $apiEvent) {
            $cacheItem = $cacheEventsResults->get($this->createResultKey($apiEvent->api_id));
            if ($cacheItem) {
                $eventsResults->push($cacheItem);
            } else {
                $eventsIdsToRequest->push($apiEvent->api_id);
            }
        }

        // Call API for data in 10-elements chunks
        foreach ($eventsIdsToRequest->chunk(10) as $keys) {
            $results = collect($this->api->getResults($keys->values()));
            $this->cache->setMultiple(
                $results->mapWithKeys(
                    fn(array $item) => [$this->createResultKey($item["bet365_id"]) => $item],
                ),
                static::RESULTS_CACHE_TTL,
            );
            $eventsResults->push(...$results);
        }

        return collect($eventsResults)
            ->map(function ($item) {
                [$home, $away] = $this->extractScore(Arr::get($item, "ss"));

                return new SportEventResult(
                    $item["bet365_id"],
                    $this->mapTimeStatus($item),
                    $home,
                    $away,
                );
            })
            ->all();
    }

    public function getSports(): array
    {
        $sports = $this->entityManager->getRepository(Bet365\Model\Sport::class)->findBy(['enabled' => true]);
        $results = [];

        foreach ($sports as $sport) {
            $results[] = $sport->toSport();
        }

        return $results;
    }

    public function createPreMatchKey(string $eventId): string
    {
        return "bets365.prematch.{$eventId}";
    }

    public function createResultKey(string $eventId): string
    {
        return "bets365.result.{$eventId}";
    }

    private function mapTimeStatus(array $data): TimeStatus
    {
        $timeStatus = $data["time_status"];

        // @see https://betsapi.com/docs/GLOSSARY.html#time_status
        switch ($timeStatus) {
            case "0":
                return TimeStatus::NOT_STARTED();
            case "1":
                return TimeStatus::IN_PLAY();
            case "2":
                return TimeStatus::TO_BE_FIXED();
            case "3":
                $this->logger->info("Time status equals 'ended'.", $data);
                return TimeStatus::ENDED();
            default:
                return TimeStatus::CANCELED();
        }
    }

    /**
     * @param string|null $text
     * @return int[]
     */
    private function extractScore(?string $text): array
    {
        if (!$text) {
            return [null, null];
        }

        $exploded = explode("-", $text);

        if (count($exploded) !== 2) {
            return [null, null];
        }

        return [(int) $exploded[0], (int) $exploded[1]];
    }
}
