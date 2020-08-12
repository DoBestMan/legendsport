<?php

namespace App\Betting\SportsData;

use App\Betting\Pagination;
use App\Betting\SingleEventUpdater;
use App\Betting\Sport;
use App\Betting\SportEvent;
use App\Betting\SportEventResult;
use App\Betting\SportsData\OddsFilters\HasOddsFromChosenSportsbook;
use App\Betting\SportsData\OddsFilters\MainLines;
use App\Betting\TimeStatus;
use App\Domain\ApiEvent;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;

class MLB extends AbstractSportsData implements SingleEventUpdater
{
    private const PREMATCH_CACHE_TTL = 120;
    public const PROVIDER_NAME = "sportsdata.io/mlb";
    public const PROVIDER_DESCRIPTION = 'SportsData.io MLB';

    public function getEvents(int $page): Pagination
    {
        $data = $this->get('https://api.sportsdata.io/v3/mlb/odds/json/BettingEvents/2020');

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
                '10002',
                $event['HomeTeam'] . ' ' . $home,
                $event['AwayTeam'] . ' ' . $away,
                self::PROVIDER_NAME
            );
        }

        return new Pagination($results, count($results), count($results));
    }

    public function getOdds(bool $updatesOnly): array
    {
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

        foreach ($apiEventDict as $apiEvent) {
            if (!$apiEvent->isFresherThan(self::PREMATCH_CACHE_TTL)) {
                $this->dispatcher->dispatch(new UpdateOddsJob($apiEvent->getId()));
                $updates[] = $apiEvent;
            }
        }

        $this->entityManager->flush();

        return $updatesOnly ? $updates : $apiEventDict;
    }

    public function getResults(): array
    {
        $data = $this->get('https://api.sportsdata.io/v3/mlb/odds/json/BettingEvents/2020');
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
        return [new Sport('10002', 'Baseball', self::PROVIDER_NAME)];
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

    public function updateEventOdds(ApiEvent $apiEvent): void
    {
        $key = $apiEvent->getApiId();

        $results = $this->get(sprintf('https://api.sportsdata.io/v3/mlb/odds/json/BettingMarkets/%s', $key));

        $this->logger->info(sprintf('Retrieving odds for events: %s', $key));

        $matchOdds = new MainLines(new HasOddsFromChosenSportsbook(new \ArrayIterator($results)));
        $preMatchOdds = $this->parser->parseMainLines(
            $matchOdds
        );

        $apiEvent->updateOdds($preMatchOdds);
    }
}
