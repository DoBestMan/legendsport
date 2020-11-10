<?php

namespace Unit\Betting\SportEvent;

use App\Betting\SportEvent\LineCollection;
use App\Betting\SportEvent\OfferCollection;
use App\Betting\SportEvent\Result;
use App\Betting\SportEvent\Update;
use App\Betting\SportEvent\UpdateCollection;
use App\Betting\TimeStatus;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Betting\SportEvent\UpdateCollection
 */
class UpdateCollectionTest extends TestCase
{
    public function testConstruct()
    {
        $result = new Result(
            'extId',
            'testdata',
            TimeStatus::ENDED(),
            '2020-10-22 00:08:00',
            4,
            6,
            'B Snell',
            'T Gonsolin',
        );

        $updates = new Update('extId', $result, new LineCollection(), new OfferCollection());
        $sut = new UpdateCollection('testdata', $updates);

        self::assertEquals('testdata', $sut->getProvider());
        self::assertContains($updates, $sut->getUpdates());
        self::assertEquals(['extId'], $sut->getExternalIds());
        self::assertTrue($sut->hasUpdate('extId'));
        self::assertFalse($sut->hasUpdate('invalidId'));
        self::assertEquals($updates, $sut->getUpdate('extId'));
    }
}
