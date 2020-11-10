<?php

namespace Unit\Betting\SportEvent;

use App\Betting\SportEvent\Sport;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Betting\SportEvent\Sport
 */
class SportTest extends TestCase
{
    public function testConstruct()
    {
        $sut = new Sport('sportID', 'Baseball', 'testdata');

        self::assertEquals('sportID', $sut->getId());
        self::assertEquals('Baseball', $sut->getName());
        self::assertEquals('testdata', $sut->getProvider());
    }
}
