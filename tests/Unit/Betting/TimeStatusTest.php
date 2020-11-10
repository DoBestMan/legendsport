<?php

namespace Unit\Betting;

use App\Betting\TimeStatus;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Betting\TimeStatus
 */
class TimeStatusTest extends TestCase
{
    /** @dataProvider provideFromApiStatus */
    public function testFromApiStatus(string $value, TimeStatus $expected)
    {
        $sut = TimeStatus::fromApiStatus($value);
        self::assertTrue($expected->equals($sut));
    }

    public function provideFromApiStatus()
    {
        return [
            ['upcoming', TimeStatus::NOT_STARTED()],
            ['cancelled', TimeStatus::CANCELED()],
            ['ended', TimeStatus::ENDED()],
            ['inplay', TimeStatus::IN_PLAY()],
            ['suspended', TimeStatus::IN_PLAY()],
        ];
    }
}
