<?php

namespace App\Betting\SportsData;

use App\Betting\BettingProvider;
use App\Betting\Pagination;
use App\Betting\Sport;
use App\Betting\SportEvent;
use App\Betting\SportEventOdd;
use App\Betting\SportEventResult;
use App\Betting\SportsData\OddsFilters\HasOddsFromChosenSportsbook;
use App\Betting\SportsData\OddsFilters\MainLines;
use App\Betting\TimeStatus;
use App\Domain\ApiEvent;
use Decimal\Decimal;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Psr\Log\LoggerInterface;

class NBA implements BettingProvider
{
    const PROVIDER_NAME = "sportsdata.io/nba";
    const PREMATCH_CACHE_TTL = 120;
    private string $apiKey;
    private EntityManager $entityManager;
    private LoggerInterface $logger;

    public function __construct(string $apiKey, EntityManager $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->apiKey = $apiKey;
    }

    public function getEvents(int $page): Pagination
    {
        //@TODO season is hard coded
        $data = Http::withHeaders([
            'Ocp-Apim-Subscription-Key' => $this->apiKey,
        ])->get('https://api.sportsdata.io/v3/nba/odds/json/BettingEvents/2020')->json();

        foreach ($data as $event) {
            if ($event['GameID'] === null) {
                continue;
            }

            if ($event['GameStatus'] !== 'Scheduled') {
                continue;
            }

            [$away, $home] = explode(' @ ', $event['Name']);

            $results[] = new SportEvent(
                $event['BettingEventID'],
                $event['StartDate'],
                '10001',
                $event['HomeTeam'] . ' ' . $home,
                $event['AwayTeam'] . ' ' . $away,
                self::PROVIDER_NAME
            );
        }

        return new Pagination($results, count($results), count($results));
    }

    public function getOdds(bool $updatesOnly): array
    {
        //https://api.sportsdata.io/v3/nba/odds/json/BettingMarkets/542
        /** @var \App\Domain\ApiEvent[]|Collection $apiEventDict */
        $qb = $this->entityManager->createQueryBuilder();
        $apiEventDict = $qb->select('a')
            ->from(\App\Domain\ApiEvent::class, 'a')
            ->where($qb->expr()->eq('a.provider', '?1'))
            ->andWhere($qb->expr()->eq('a.timeStatus', '?2'))
            ->indexBy('a', 'a.apiId')
            ->getQuery()
            ->execute([
                1 => static::PROVIDER_NAME,
                2 => TimeStatus::NOT_STARTED()->getValue(),
            ]);

        $updates = [];
        $parser = new Parser();

        foreach ($apiEventDict as $apiEvent) {
            if (!$apiEvent->isFresherThan(self::PREMATCH_CACHE_TTL)) {
                $key = $apiEvent->getApiId();

                $results = Http::withHeaders([
                    'Ocp-Apim-Subscription-Key' => $this->apiKey,
                ])->get(sprintf('https://api.sportsdata.io/v3/nba/odds/json/BettingMarkets/%s', $key))->json();

                $this->logger->info(sprintf('Retrieving odds for events: %s', $key));

                $preMatchOdds = $parser->parseMainLines(
                    new MainLines(new HasOddsFromChosenSportsbook(new \ArrayIterator($results)))
                );

                $apiEvent->updateOdds($preMatchOdds);
                $updates[] = $apiEvent;
            }
        }

        $this->entityManager->flush();

        return $updatesOnly ? $updates : $apiEventDict;
    }

    public function getResults(): array
    {
        $data = Http::withHeaders([
            'Ocp-Apim-Subscription-Key' => $this->apiKey,
        ])->get('https://api.sportsdata.io/v3/nba/odds/json/BettingEvents/2020')->json();

        $results = [];

        foreach ($data as $event) {
            if ($event['GameID'] === null) {
                continue;
            }

            //Scheduled, InProgress, Final, Suspended, Postponed, Canceled
            if ($event['GameStatus'] === 'Scheduled') {
                continue;
            }

            /** @var ApiEvent|null $apiEvent */
            $apiEvent = current($this->entityManager->getRepository(ApiEvent::class)->findBy([
                'apiId' => $event['BettingEventID'],
                'provider' => self::PROVIDER_NAME,
            ])) ?: null;

            if ($apiEvent === null) {
                continue;
            }

            if ($apiEvent->isFinished()) {
                continue;
            }

            $timeStatus = $this->mapTimeStatus($event['GameStatus']);
            $result = new SportEventResult($event['BettingEventID'], $timeStatus, $event['HomeTeamScore'], $event['AwayTeamScore']);
            $apiEvent->result($result);
            $results[] = $result;
        }

        return $results;
    }

    public function getSports(): array
    {
        return [new Sport('10001', 'Basketball', self::PROVIDER_NAME)];
    }

    private function mapTimeStatus(string $status): TimeStatus
    {
        //Scheduled, InProgress, Final, Suspended, Postponed, Canceled
        switch ($status) {
            case 'Scheduled':
                return TimeStatus::NOT_STARTED();
            case 'InProgress':
                return TimeStatus::IN_PLAY();
            case 'Suspended':
            case 'Postponed':
                return TimeStatus::TO_BE_FIXED();
            case "Final":
                return TimeStatus::ENDED();
            default:
                return TimeStatus::CANCELED();
        }
    }
}
