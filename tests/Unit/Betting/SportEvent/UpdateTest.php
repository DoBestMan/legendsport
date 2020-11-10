<?php

namespace Unit\Betting\SportEvent;

use App\Betting\SportEvent\LineCollection;
use App\Betting\SportEvent\OfferCollection;
use App\Betting\SportEvent\Result;
use App\Betting\SportEvent\Update;
use App\Betting\TimeStatus;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Betting\SportEvent\Update
 * @uses \App\Betting\SportEvent\Result
 * @uses \App\Betting\SportEvent\LineCollection
 * @uses \App\Betting\SportEvent\OfferCollection
 */
class UpdateTest extends TestCase
{
    public function testConstruct()
    {
        $result = new Result(
            6071474,
            'testdata',
            TimeStatus::ENDED(),
            '2020-10-22 00:08:00',
            4,
            6,
            'B Snell',
            'T Gonsolin',
        );

        $sut = new Update('extId', $result, new LineCollection(), new OfferCollection());

        self::assertEquals('extId', $sut->getExternalId());
        self::assertEquals($result, $sut->getResult());
        self::assertEquals(new LineCollection(), $sut->getLines());
        self::assertEquals(new OfferCollection(), $sut->getOffers());
    }
}
