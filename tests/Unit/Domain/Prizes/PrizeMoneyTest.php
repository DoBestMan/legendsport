<?php

namespace Unit\Domain\Prizes;

use App\Domain\Prizes\Prize;
use App\Domain\Prizes\PrizeMoney;
use PHPUnit\Framework\TestCase;

/**
 * @covers  \App\Domain\Prizes\PrizeMoney
 */
class PrizeMoneyTest extends TestCase
{
    public function testConstruct()
    {
        $sut = new PrizeMoney(7, 1000);
        self::assertEquals(7, $sut->getMaxPosition());
        self::assertEquals(1000, $sut->getPrizeMoney());
    }
}
