<?php

namespace Unit\Domain\Prizes;

use App\Domain\Prizes\Prize;
use App\Domain\Prizes\PrizeMoney;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Domain\Prizes\Prize
 * @uses \App\Domain\Prizes\PrizeMoney
 */
class PrizeTest extends TestCase
{
    public function testConstruct()
    {
        $sut = new Prize(7, 20.0);
        self::assertEquals(7, $sut->getMaxPosition());
        self::assertEquals(20.0, $sut->getPrizePercentage());
        self::assertEquals(0.2, $sut->asDecimal());
    }

    public function testToPrizeMoney()
    {
        $sut = new Prize(7, 20.0);
        self::assertEquals(new PrizeMoney(7, 10000), $sut->toPrizeMoney(50000));
    }
}
