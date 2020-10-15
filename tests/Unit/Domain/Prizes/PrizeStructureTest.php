<?php

namespace Unit\Domain\Prizes;

use App\Domain\Prizes\PrizeStructure;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Domain\Prizes\PrizeStructure
 * @uses \App\Domain\Prizes\Prize
 * @uses \App\Domain\Prizes\PrizeCollection
 */
class PrizeStructureTest extends TestCase
{
    /** @dataProvider provideGetStructure */
    public function testGetStructure(int $players, int $expectedPrizes)
    {
        $prizeCollection = PrizeStructure::getStructure($players);
        self::assertCount($expectedPrizes, $prizeCollection->getPrizes());
    }

    public function provideGetStructure()
    {
        return [
            [0, 1],
            [1, 1],
            [2, 1],
            [3, 3],
            [10, 3],
            [100, 12],
            [1000, 20],
        ];
    }
}
