<?php

namespace App\Betting\Bet365\Parser;

use App\Betting\SportEventOdd;
use Decimal\Decimal;

class Tennis
{
    public function parseMainLines(array $apiResult, string $homeTeamName, string $awayTeamName)
    {
        $moneyLineHome = null;
        $moneyLineAway = null;
        $pointSpreadHome = null;
        $pointSpreadAway = null;
        $pointSpreadHomeLine = null;
        $pointSpreadAwayLine = null;
        $overLine = null;
        $underLine = null;
        $totalNumber = null;

        $moneyLine = $apiResult['main']['sp']['to_win_match'];
        $spread = $apiResult['main']['sp']['match_handicap_(games)'];
        $totals = $apiResult['main']['sp']['total_games_2_way'];

        foreach ($moneyLine as $item) {
            $odds = decimal_to_american($item['odds']);
            if ($this->cmpStr($item['name'], $homeTeamName)) {
                $moneyLineHome = $odds;
            }

            if ($this->cmpStr($item['name'], $awayTeamName)) {
                $moneyLineAway = $odds;
            }
        }

        foreach ($spread as $item) {
            $odds = decimal_to_american($item['odds']);
            $handicap = new Decimal($item['handicap']);

            if ($this->cmpStr($item['header'], $homeTeamName)) {
                $pointSpreadHome = $odds;
                $pointSpreadHomeLine = $handicap;
            }

            if ($this->cmpStr($item['header'], $awayTeamName)) {
                $pointSpreadAway = $odds;
                $pointSpreadAwayLine = $handicap;
            }
        }

        foreach ($totals as $item) {
            $odds = decimal_to_american($item['odds']);
            $handicap = new Decimal($item['handicap']);

            if ($this->cmpStr($item['header'], 'Over')) {
                $overLine = $odds;
                $totalNumber = $handicap;
            }

            if ($this->cmpStr($item['header'], 'Under')) {
                $underLine = $odds;
                $totalNumber = $handicap;
            }
        }

        return new SportEventOdd(
            $apiResult['FI'],
            $moneyLineHome,
            $moneyLineAway,
            $pointSpreadHome,
            $pointSpreadAway,
            $pointSpreadHomeLine,
            $pointSpreadAwayLine,
            $overLine,
            $underLine,
            $totalNumber,
        );
    }

    private function cmpStr(string $a, string $b): bool
    {
        return strtolower(substr(trim($a), 0, strlen($b))) === strtolower(trim($b));
    }
}
