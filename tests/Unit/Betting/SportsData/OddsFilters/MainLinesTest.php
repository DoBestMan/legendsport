<?php

namespace Unit\Betting\SportsData\OddsFilters;

use App\Betting\SportsData\OddsFilters\HasOddsFromChosenSportsbook;
use App\Betting\SportsData\OddsFilters\MainLines;
use PHPUnit\Framework\TestCase;

class MainLinesTest extends TestCase
{
    public function testIterate()
    {
        $data = [
            [
                "BettingBetTypeID"=> 3,
                "BettingMarketTypeID"=> 1,
                "BettingPeriodTypeID"=> 1,
            ],
            [
                "BettingBetTypeID"=> 2,
                "BettingMarketTypeID"=> 1,
                "BettingPeriodTypeID"=> 1,
            ],
            [
                "BettingBetTypeID"=> 1,
                "BettingMarketTypeID"=> 1,
                "BettingPeriodTypeID"=> 1,
            ],
            [
                "BettingBetTypeID"=> 1,
                "BettingMarketTypeID"=> 3,
                "BettingPeriodTypeID"=> 1,
            ],
            [
                "BettingBetTypeID"=> 1,
                "BettingMarketTypeID"=> 1,
                "BettingPeriodTypeID"=> 4,
            ],
            [
                "BettingBetTypeID"=> 7,
                "BettingMarketTypeID"=> 1,
                "BettingPeriodTypeID"=> 1,
            ],
        ];
        $sut = new MainLines(new \ArrayIterator($data));

        $result = iterator_to_array($sut);
        self::assertCount(3, $result);
    }
}
