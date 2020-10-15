<?php

namespace Unit\Domain\Prizes;

use App\Domain\Prizes\Prize;
use App\Domain\Prizes\PrizeCollection;
use App\Domain\Prizes\PrizeMoney;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Domain\Prizes\PrizeCollection
 * @uses \App\Domain\Prizes\Prize
 * @uses \App\Domain\Prizes\PrizeMoney
 * @uses \App\Domain\Prizes\PrizeMoneyCollection
 */
class PrizeCollectionTest extends TestCase
{
    public function testConstruct()
    {
        $prizes = [
            new Prize(1, 20.0),
            new Prize(5, 60.0),
            new Prize(10, 40.0),
        ];

        $sut = new PrizeCollection(...$prizes);

        self::assertCount(3, $sut->getPrizes());
    }

    public function testToPrizeMoneyCollection()
    {
        $prizes = [
            new Prize(1, 20.0),
            new Prize(5, 60.0),
            new Prize(10, 40.0),
        ];

        $sut = new PrizeCollection(...$prizes);

        $result = $sut->toPrizeMoneyCollection(10000);
        $firstPrize = current($result->toArray());

        self::assertCount(3, $result->toArray());
        self::assertEquals(2000, $firstPrize->getPrizeMoney());
    }
}
