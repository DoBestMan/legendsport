<?php

namespace Unit\Betting\SportEvent;

use App\Betting\SportEvent\Result;
use App\Betting\TimeStatus;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Betting\SportEvent\Result
 */
class ResultTest extends TestCase
{
    public function testConstruct()
    {
        $sut = new Result(
            6071474,
            'testdata',
            TimeStatus::ENDED(),
            '2020-10-22 00:08:00',
            4,
            6,
            'B Snell',
            'T Gonsolin',
        );

        self::assertEquals(6071474, $sut->getExternalEventId());
        self::assertEquals('testdata', $sut->getProvider());
        self::assertEquals(4, $sut->getHome());
        self::assertEquals(6, $sut->getAway());
        self::assertEquals('B Snell', $sut->getHomePitcher());
        self::assertEquals('T Gonsolin', $sut->getAwayPitcher());
        self::assertTrue(TimeStatus::ENDED()->equals($sut->getTimeStatus()));
        self::assertEquals(new Carbon('2020-10-22 00:08:00'), $sut->getStartsAt());
    }
}
