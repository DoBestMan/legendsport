<?php

namespace App\Betting\SportsData;

use App\Betting\BettingProvider;
use App\Betting\Pagination;
use App\Betting\Sport;
use App\Betting\SportEvent;
use App\Betting\SportEventResult;
use App\Betting\SportsData\OddsFilters\HasOddsFromChosenSportsbook;
use App\Betting\SportsData\OddsFilters\MainLines;
use App\Betting\TimeStatus;
use App\Domain\ApiEvent;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Illuminate\Bus\Dispatcher;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Psr\Log\LoggerInterface;

abstract class AbstractSportsData implements BettingProvider
{
    private const PREMATCH_CACHE_TTL = 120;
    protected const SCORES_API_KEY = 'scores';
    protected const ODDS_API_KEY = 'odds';

    protected EntityManager $entityManager;
    protected Parser $parser;
    protected Dispatcher $dispatcher;
    private string $scoresApiKey;
    private string $oddsApiKey;
    protected LoggerInterface $logger;

    public function __construct(string $scoresApiKey, string $oddsApiKey, EntityManager $entityManager, LoggerInterface $logger, Dispatcher $dispatcher)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->scoresApiKey = $scoresApiKey;
        $this->parser = new Parser();
        $this->dispatcher = $dispatcher;
        $this->oddsApiKey = $oddsApiKey;
    }

    public function get(string $url, string $key): array
    {
        switch ($key) {
            case self::SCORES_API_KEY:
                $apiKey = $this->scoresApiKey;
                break;
            case self::ODDS_API_KEY:
                $apiKey = $this->oddsApiKey;
                break;
            default:
                throw new \RuntimeException('Invalid API key requested');
        }
        $result = Http::withHeaders([
            'Ocp-Apim-Subscription-Key' => $apiKey,
        ])->timeout(60)->get($url);

        $data = $result->json();

        if (!$result->successful()) {
            $message = '';
            if (isset($data['message'])) {
                $message = $data['message'];
            } elseif (isset($data['Description'])) {
                $message = $data['Description'];
            }

            throw new \RuntimeException(sprintf('Unable to communicate with API (%s): %s', $url, $message));
        }

        return $data;
    }

    public function getOdds(bool $updatesOnly): array
    {
        /** @var ApiEvent[]|Collection $apiEventDict */
        $qb = $this->entityManager->createQueryBuilder();
        $apiEventDict = $qb->select('a')
            ->from(ApiEvent::class, 'a')
            ->where($qb->expr()->eq('a.provider', '?1'))
            ->andWhere($qb->expr()->eq('a.timeStatus', '?2'))
            ->getQuery()
            ->execute([
                1 => static::PROVIDER_NAME,
                2 => TimeStatus::NOT_STARTED()->getValue(),
            ]);


        foreach ($apiEventDict as $apiEvent) {
            if (!$apiEvent->isFresherThan(self::PREMATCH_CACHE_TTL)) {
                $this->dispatcher->dispatch(new UpdateOddsJob($apiEvent->getId()));
            }
        }

        return $updatesOnly ? [] : $apiEventDict;
    }

    public function getResults(): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        /** @var ApiEvent[] $apiEventDict */
        $apiEventDict = $qb->select('a')
            ->from(ApiEvent::class, 'a')
            ->where($qb->expr()->eq('a.provider', '?1'))
            ->andWhere($qb->expr()->notIn('a.timeStatus', '?2'))
            ->getQuery()
            ->execute([
                1 => static::PROVIDER_NAME,
                2 => [TimeStatus::CANCELED()->getValue(), TimeStatus::ENDED()->getValue()],
            ]);


        foreach ($apiEventDict as $apiEvent) {
            $this->dispatcher->dispatch(new FetchResultsForDate($apiEvent->getStartsAt(), static::PROVIDER_NAME, 0));
            $this->dispatcher->dispatch(new FetchResultsForDate($apiEvent->getStartsAt(), static::PROVIDER_NAME, 15));
            $this->dispatcher->dispatch(new FetchResultsForDate($apiEvent->getStartsAt(), static::PROVIDER_NAME, 30));
            $this->dispatcher->dispatch(new FetchResultsForDate($apiEvent->getStartsAt(), static::PROVIDER_NAME, 45));
        }

        return [];
    }

    public function getSports(): array
    {
        return [new Sport(static::SPORT_ID, static::SPORT_NAME, static::PROVIDER_NAME)];
    }

    public function getEvents(int $page): Pagination
    {
        $date = Carbon::now();
        $date->addDays($page - 1);
        $data = $this->get(sprintf(static::URLS['bettingEventsByDate'], $date->format('Y-m-d')), AbstractSportsData::ODDS_API_KEY);
        $results = $this->parseEvents($data);
        $count = count($results);
        return new Pagination($results, $count, $count);
    }

    public function getResultsForDate(string $date): array
    {
        $data = $this->get(sprintf(static::URLS['gamesByDate'], $date), AbstractSportsData::SCORES_API_KEY);
        return $this->parseResults($data);
    }

    protected function mapTimeStatus(string $status): TimeStatus
    {
        //Scheduled, InProgress, Final, Suspended, Postponed, Canceled
        switch ($status) {
            case 'Scheduled':
                return TimeStatus::NOT_STARTED();
            case 'Suspended':
            case 'InProgress':
                return TimeStatus::IN_PLAY();
            case "Final":
            case "F/OT":
                return TimeStatus::ENDED();
            case "Canceled":
            case 'Postponed':
                return TimeStatus::CANCELED();
            default:
                return TimeStatus::IN_PLAY();
        }
    }

    protected function parseEvents(array $data): array
    {
        $results = [];

        foreach ($data as $event) {
            if (!$this->isValidGame($event)) {
                continue;
            }

            if ($event['GameStatus'] !== 'Scheduled') {
                continue;
            }

            [$away, $home] = explode(' @ ', $event['Name']);

            $startDate = Carbon::createFromFormat('Y-m-d\TH:i:s', $event['StartDate'], 'America/New_York');
            $startDate->setTimezone('UTC');

            $results[] = new SportEvent(
                $this->getGameId($event),
                $startDate->format('Y-m-d\TH:i:s'),
                static::SPORT_ID,
                $event['HomeTeam'] . ' ' . $home,
                $event['AwayTeam'] . ' ' . $away,
                static::PROVIDER_NAME
            );
        }

        return $results;
    }

    protected function parseResults(array $data): array
    {
        $results = [];

        foreach ($data as $event) {
            $externalEventId = $event[static::DATA_KEY_MAP['gameId']];

            if ($externalEventId === null) {
                continue;
            }

            //Scheduled, InProgress, Final, Suspended, Postponed, Canceled
            if ($event[static::DATA_KEY_MAP['status']] === 'Scheduled') {
                continue;
            }

            /** @var ApiEvent|null $apiEvent */
            $apiEvent = current($this->entityManager->getRepository(ApiEvent::class)->findBy([
                'apiId' => $externalEventId,
                'provider' => static::PROVIDER_NAME,
            ])) ?: null;

            if ($apiEvent === null) {
                continue;
            }

            if ($apiEvent->isFinished()) {
                continue;
            }

            $timeStatus = $this->mapTimeStatus($event[static::DATA_KEY_MAP['status']]);
            $result = new SportEventResult(
                $externalEventId,
                static::PROVIDER_NAME,
                $timeStatus,
                $event[static::DATA_KEY_MAP['homeScore']],
                $event[static::DATA_KEY_MAP['awayScore']]
            );

            $results[] = $result;
        }
        return $results;
    }

    private function isValidGame($event): bool
    {
        return $this->getGameId($event) !== null;
    }

    private function getGameId($event)
    {
        $gameId = null;
        if (isset($event['GameID'])) {
            $gameId = $event['GameID'];
        } elseif (isset($event['ScoreID'])) {
            $gameId = $event['ScoreID'];
        }
        return $gameId;
    }

    public function updateEventOdds(ApiEvent $apiEvent): void
    {
        $key = $apiEvent->getApiId();

        $results = $this->get(sprintf(static::URLS['marketsById'], $key), AbstractSportsData::ODDS_API_KEY);

        $this->logger->info(sprintf('Retrieving odds for events: %s', $key));

        $preMatchOdds = $this->parser->parseMainLines(
            new MainLines(new HasOddsFromChosenSportsbook(new \ArrayIterator($results)))
        );

        $apiEvent->updateOdds($preMatchOdds);
    }
}
