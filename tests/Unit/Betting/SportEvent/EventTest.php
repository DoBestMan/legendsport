<?php

namespace Unit\Betting\SportEvent;

use App\Betting\SportEvent\Event;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Betting\SportEvent\Event
 */
class EventTest extends TestCase
{
    public function testConstruct()
    {
        $sut = new Event(
            'exId',
            '2020-11-06 17:00:00',
            1234,
            'home',
            'away',
            'testdata',
            'home pitcher',
            'away pitcher'
        );

        self::assertEquals('exId', $sut->getExternalId());
        self::assertEquals(1234, $sut->getSportId());
        self::assertEquals('home', $sut->getHomeTeam());
        self::assertEquals('away', $sut->getAwayTeam());
        self::assertEquals('testdata', $sut->getProvider());
        self::assertEquals('home pitcher', $sut->getHomePitcher());
        self::assertEquals('away pitcher', $sut->getAwayPitcher());
        self::assertEquals(new Carbon('2020-11-06 17:00:00'), $sut->getStartsAt());
    }
}
