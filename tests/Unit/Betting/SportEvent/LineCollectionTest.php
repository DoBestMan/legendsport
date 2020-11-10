<?php

namespace Unit\Betting\SportEvent;

use App\Betting\SportEvent\Line;
use App\Betting\SportEvent\LineCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Betting\SportEvent\LineCollection
 * @uses \App\Betting\SportEvent\Line
 */
class LineCollectionTest extends TestCase
{
    public function testConstruct()
    {
        $line = new Line('ml::h::ft', 175, null, null);
        $sut = new LineCollection($line);

        self::assertCount(1, $sut->getLines());
        self::assertContains($line, $sut->getLines());
    }

    public function testConstructEmpty()
    {
        $sut = new LineCollection();

        self::assertEmpty($sut->getLines());
    }
}
