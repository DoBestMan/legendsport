<?php

namespace Unit\Betting;

use App\Betting\Settlement;
use App\Tournament\Enums\BetStatus;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Betting\Settlement
 */
class SettlementTest extends TestCase
{
    /** @dataProvider provideFromApiSettlement */
    public function testFromApiSettlement(string $value, ?Settlement $expected)
    {
        $sut = Settlement::fromApiSettlement($value);
        if ($expected === null) {
            self::assertNull($sut);
        } else {
            self::assertTrue($expected->equals($sut));
        }
    }

    public function provideFromApiSettlement()
    {
        return [
            ['', null],
            ['Won', Settlement::WON()],
            ['Lost', Settlement::LOST()],
            ['Push', Settlement::PUSH()],
        ];
    }

    /** @dataProvider provideToBetStatus */
    public function testToBetStatus(string $value, BetStatus $expected)
    {
        $sut = Settlement::fromApiSettlement($value);

        self::assertTrue($expected->equals($sut->toBetStatus()));
    }

    public function provideToBetStatus()
    {
        return [
            ['Won', BetStatus::WIN()],
            ['Lost', BetStatus::LOSS()],
            ['Push', BetStatus::PUSH()],
        ];
    }
}
