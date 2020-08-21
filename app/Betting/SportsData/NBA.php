<?php

namespace App\Betting\SportsData;

use App\Betting\SingleEventUpdater;

class NBA extends AbstractSportsData implements SingleEventUpdater
{
    public const PROVIDER_NAME = "sportsdata.io/nba";
    public const PROVIDER_DESCRIPTION = 'SportsData.io NBA';
    public const SPORT_ID = '10001';
    public const SPORT_NAME = 'Basketball';

    protected const DATA_KEY_MAP = [
        'gameId' => 'GameID',
        'status' => 'Status',
        'homeScore' => 'HomeTeamScore',
        'awayScore' => 'AwayTeamScore',
    ];
    protected const URLS = [
        'gamesByDate' => 'https://api.sportsdata.io/v3/nba/scores/json/GamesByDate/%s',
        'bettingEventsByDate' => 'https://api.sportsdata.io/v3/nba/odds/json/BettingEventsByDate/%s',
        'marketsById' => 'https://api.sportsdata.io/v3/nba/odds/json/BettingMarketsByGameID/%s',
    ];
}
