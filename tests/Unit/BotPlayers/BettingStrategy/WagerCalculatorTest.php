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
            [1, 1000],
            [2, 1000],
            [3, 1000],
            [5, 1000],
            [8, 1000],
            [10, 1000],
            [25, 1000],
        ];
    }
}
