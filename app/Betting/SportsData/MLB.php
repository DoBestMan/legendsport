<?php

namespace App\Betting\SportsData;

use App\Betting\Pagination;
use App\Betting\SingleEventUpdater;
use App\Betting\Sport;
use App\Betting\SportsData\OddsFilters\HasOddsFromChosenSportsbook;
use App\Betting\SportsData\OddsFilters\MainLines;
use App\Betting\TimeStatus;
use App\Domain\ApiEvent;
use Carbon\Carbon;

class MLB extends AbstractSportsData implements SingleEventUpdater
{
    public const PROVIDER_NAME = "sportsdata.io/mlb";
    public const PROVIDER_DESCRIPTION = 'SportsData.io MLB';
    public const SPORT_ID = '10002';
    public const SPORT_NAME = 'Baseball';

    protected const DATA_KEY_MAP = [
        'gameId' => 'GameID',
        'status' => 'Status',
        'homeScore' => 'HomeTeamRuns',
        'awayScore' => 'AwayTeamRuns',
    ];

    protected const URLS = [
        'gamesByDate' => 'https://api.sportsdata.io/v3/mlb/scores/json/GamesByDate/%s',
        'bettingEventsByDate' => 'https://api.sportsdata.io/v3/mlb/odds/json/BettingEventsByDate/%s',
        'marketsById' => 'https://api.sportsdata.io/v3/mlb/odds/json/BettingMarketsByGameID/%s',
    ];
}
