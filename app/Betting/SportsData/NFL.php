<?php

namespace App\Betting\SportsData;

use App\Betting\Pagination;
use App\Betting\SingleEventUpdater;
use App\Betting\Sport;
use App\Betting\SportsData\OddsFilters\HasOddsFromChosenSportsbook;
use App\Betting\SportsData\OddsFilters\MainLines;
use App\Domain\ApiEvent;

class NFL extends AbstractSportsData implements SingleEventUpdater
{
    public const PROVIDER_NAME = "sportsdata.io/nfl";
    public const PROVIDER_DESCRIPTION = 'SportsData.io NFL';
    public const SPORT_ID = '10003';

    protected const DATA_KEY_MAP = [
        'gameId' => 'ScoreID',
        'status' => 'Status',
        'homeScore' => 'HomeTeamRuns',
        'awayScore' => 'AwayTeamRuns',
    ];

    public function getEvents(int $page): Pagination
    {
        $data = $this->get('https://api.sportsdata.io/v3/nfl/odds/json/BettingEvents/2020', self::ODDS_API_KEY);
        $results = $this->parseEvents($data);
        $count = count($results);
        return new Pagination($results, $count, $count);
    }

    public function getResults(): array
    {
        $data = $this->get('https://api.sportsdata.io/v3/nfl/odds/json/BettingEvents/2020', self::SCORES_API_KEY);
        return $this->parseResults($data);
    }

    public function getSports(): array
    {
        return [new Sport('10003', 'Football', self::PROVIDER_NAME)];
    }

    public function updateEventOdds(ApiEvent $apiEvent): void
    {
        $key = $apiEvent->getApiId();

        $results = $this->get(sprintf('https://api.sportsdata.io/v3/nfl/odds/json/BettingMarketsByGameID/%s', $key), self::ODDS_API_KEY);

        $this->logger->info(sprintf('Retrieving odds for events: %s', $key));

        $preMatchOdds = $this->parser->parseMainLines(
            new MainLines(new HasOddsFromChosenSportsbook(new \ArrayIterator($results)))
        );

        $apiEvent->updateOdds($preMatchOdds);
    }
}
