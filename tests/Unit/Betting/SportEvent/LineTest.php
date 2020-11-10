<?php

namespace Unit\Betting\SportEvent;

use App\Betting\Settlement;
use App\Betting\SportEvent\Line;
use Decimal\Decimal;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Betting\SportEvent\Line
 * @uses \App\Betting\Settlement
 */
class LineTest extends TestCase
{
    public function testConstruct()
    {
        $sut = new Line('lineId', 125, new Decimal('1.5'), Settlement::WON());

        self::assertEquals('lineId', $sut->getId());
        self::assertEquals(125, $sut->getPrice());
        self::assertEquals(new Decimal('1.5'), $sut->getLine());
        self::assertTrue(Settlement::WON()->equals($sut->getSettlement()));
    }
}
