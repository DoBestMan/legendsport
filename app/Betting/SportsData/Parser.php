<?php

namespace App\Betting\SportsData;

use App\Betting\SportEventOdd;
use Decimal\Decimal;

class Parser
{
    public function parseMainLines(iterable $preMatchOdds)
    {
        $homeMoneyLine = $awayMoneyLine = $homeSpread = $awaySpread = $homeSpreadLine = $awaySpreadLine = $totalNumber = $overLine = $underLine = null;

        foreach ($preMatchOdds as $preMatchOdd) {
            foreach ($preMatchOdd['BettingOutcomes'] as $bettingOutcome) {
                switch (true) {
                    case $preMatchOdd['BettingBetTypeID'] === 1 && $bettingOutcome['BettingOutcomeTypeID'] === 1:
                        $homeMoneyLine = $bettingOutcome['PayoutAmerican'];
                        break;
                    case $preMatchOdd['BettingBetTypeID'] === 1 && $bettingOutcome['BettingOutcomeTypeID'] === 2:
                        $awayMoneyLine = $bettingOutcome['PayoutAmerican'];
                        break;
                    case $preMatchOdd['BettingBetTypeID'] === 2 && $bettingOutcome['BettingOutcomeTypeID'] === 1:
                        $homeSpread = $bettingOutcome['PayoutAmerican'];
                        $homeSpreadLine = new Decimal((string) $bettingOutcome['Value']);
                        break;
                    case $preMatchOdd['BettingBetTypeID'] === 2 && $bettingOutcome['BettingOutcomeTypeID'] === 2:
                        $awaySpread = $bettingOutcome['PayoutAmerican'];
                        $awaySpreadLine = new Decimal((string) $bettingOutcome['Value']);
                        break;
                    case $preMatchOdd['BettingBetTypeID'] === 3 && $bettingOutcome['BettingOutcomeTypeID'] === 3:
                        $overLine = $bettingOutcome['PayoutAmerican'];
                        $totalNumber = new Decimal((string) $bettingOutcome['Value']);
                        break;
                    case $preMatchOdd['BettingBetTypeID'] === 3 && $bettingOutcome['BettingOutcomeTypeID'] === 4:
                        $underLine = $bettingOutcome['PayoutAmerican'];
                        $totalNumber = new Decimal((string) $bettingOutcome['Value']);
                        break;
                }
            }
        }

        return new SportEventOdd(
            '',
            $homeMoneyLine,
            $awayMoneyLine,
            $homeSpread,
            $awaySpread,
            $homeSpreadLine,
            $awaySpreadLine,
            $overLine,
            $underLine,
            $totalNumber
        );
    }
}
