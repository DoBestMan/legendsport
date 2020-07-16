<?php

namespace App\Betting\Bet365\Parser;

use App\Betting\Bet365\Parser\Exception\PathNotFound;
use App\Betting\SportEventOdd;

class Soccer extends AbstractParser
{
    protected string $moneyLineTeamField = 'name';
    protected string $spreadTeamField = 'header';
    protected string $totalsTypeField = 'header';
    protected array $moneyLinePath = ['main', 'sp', 'draw_no_bet'];
    protected array $spreadPath = ['main', 'sp', 'asian_handicap'];
    protected array $totalsPath = ['main', 'sp', 'goals_over_under'];

    public function parseMainLines(array $apiResult, string $homeTeamName, string $awayTeamName)
    {
        try {
            $moneyLine = $this->extractOddsGroup($apiResult, $this->moneyLinePath);
            [$moneyLineHome, $moneyLineAway] = $this->extractMoneyLine(
                $moneyLine,
                $homeTeamName,
                $awayTeamName
            );
        } catch (PathNotFound $e) {
            $moneyLineHome = $moneyLineAway = null;
            $this->errors[] = ['Money line not found', ['path' => $this->moneyLinePath, 'availableOdds' => $this->getAvailableOdds($apiResult)]];
        }

        try {
            $spread = $this->extractOddsGroup($apiResult, $this->spreadPath);
            [$pointSpreadHome, $pointSpreadHomeLine, $pointSpreadAway, $pointSpreadAwayLine] = $this->extractSpread(
                $spread,
                '1',
                '2'
            );
        } catch (PathNotFound $e) {
            $pointSpreadHome = $pointSpreadHomeLine = $pointSpreadAway = $pointSpreadAwayLine = null;
            $this->errors[] = ['Spread not found', ['path' => $this->spreadPath, 'availableOdds' => $this->getAvailableOdds($apiResult)]];
        }

        try {
            $totals = $this->extractOddsGroup($apiResult, $this->totalsPath);
            [$overLine, $totalNumber, $underLine] = $this->extractTotals(
                $totals
            );
        } catch (PathNotFound $e) {
            $overLine = $totalNumber = $underLine = null;
            $this->errors[] = ['Totals not found', ['path' => $this->totalsPath, 'availableOdds' => $this->getAvailableOdds($apiResult)]];
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
}
