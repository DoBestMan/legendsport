<?php

namespace Unit\BotPlayers\BettingStrategy;

use App\BotPlayers\BettingStrategy\WagerCalculator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\BotPlayers\BettingStrategy\WagerCalculator
 */
class WagerCalculatorTest extends  TestCase
{
    /** @dataProvider provideCalculateWagers */
    public function testCalculateWagers($numberOfBets, $totalChips)
    {
        $sut = new WagerCalculator();
        $wagers = $sut->calculateWagers($totalChips, $numberOfBets);
        self::assertCount($numberOfBets, $wagers);
        self::assertEquals($totalChips, array_reduce($wagers, fn ($x, $y) => $x + $y));
    }

    public function provideCalculateWagers()
    {
        return [
            [1, 10000],
            [2, 10000],
            [3, 10000],
            [5, 10000],
            [8, 10000],
            [10, 10000],
            [25, 10000],
        ];
    }
}
