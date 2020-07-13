<?php

namespace App\Betting\Bet365\Parser;

use App\Betting\SportEventOdd;
use Decimal\Decimal;

class Soccer
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

        $moneyLine = $apiResult['main']['sp']['draw_no_bet'];
        $spread = $apiResult['main']['sp']['asian_handicap'];
        $totals = $apiResult['main']['sp']['goals_over_under'];

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

            if ($item['header'] === '1') {
                $pointSpreadHome = $odds;
                $pointSpreadHomeLine = $handicap;
            }

            if ($item['header'] === '2') {
                $pointSpreadAway = $odds;
                $pointSpreadAwayLine = $handicap;
            }
        }

        foreach ($totals as $item) {
            $odds = decimal_to_american($item['odds']);
            $handicap = new Decimal($item['handicap']);

            if ($item['header'] === 'Over') {
                $overLine = $odds;
                $totalNumber = $handicap;
            }

            if ($item['header'] === 'Under') {
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
        return strtolower(trim($a)) === strtolower(trim($b));
    }
}
