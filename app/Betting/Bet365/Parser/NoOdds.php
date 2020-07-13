<?php

namespace App\Betting\Bet365\Parser;

use App\Betting\SportEventOdd;

class NoOdds
{
    public function parseMainLines(array $apiResult, string $homeTeamName, string $awayTeamName)
    {
        return new SportEventOdd($apiResult['FI']);
    }
}
