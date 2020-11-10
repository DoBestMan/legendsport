<?php

namespace Unit\Domain;

use App\Betting\SportEvent\Line;
use App\Betting\SportEvent\Offer;
use App\Betting\SportEvent\Result;
use App\Betting\TimeStatus;
use App\Domain\ApiEvent;
use Carbon\Carbon;
use Decimal\Decimal;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Domain\ApiEvent
 * @uses \App\Domain\ApiEventOdds
 */
class ApiEventTest extends TestCase
{
    public function testResult()
    {
        $sut = new ApiEvent();
        $updated = $sut->result(new Result(
            'eid',
            'test',
            TimeStatus::NOT_STARTED(),
            '2020-10-01 18:08:00',
            null,
            null,
            null,
            null
        ));

        self::assertTrue($updated);
        self::assertEquals(new Carbon('2020-10-01 18:08:00'), $sut->getStartsAt());
        self::assertTrue(TimeStatus::NOT_STARTED()->equals($sut->getTimeStatus()));
        self::assertNull($sut->getScoreHome());
        self::assertNull($sut->getScoreAway());
        self::assertNull($sut->getPitcherHome());
        self::assertNull($sut->getPitcherAway());
    }

    public function testResultNoUpdate()
    {
        $sut = new ApiEvent();
        $sut->result(new Result(
            'eid',
            'test',
            TimeStatus::NOT_STARTED(),
            '2020-10-01 18:08:00',
            null,
            null,
            null,
            null
        ));

        $updated = $sut->result(new Result(
            'eid',
            'test',
            TimeStatus::NOT_STARTED(),
            '2020-10-01 18:08:00',
            null,
            null,
            null,
            null
        ));

        self::assertFalse($updated);
    }

    public function testResultToInPlay()
    {
        $sut = new ApiEvent();
        $sut->result(new Result(
            'eid',
            'test',
            TimeStatus::NOT_STARTED(),
            '2020-10-01 18:08:00',
            null,
            null,
            null,
            null
        ));

        $updated = $sut->result(new Result(
            'eid',
            'test',
            TimeStatus::IN_PLAY(),
            '2020-10-01 18:08:00',
            0,
            1,
            null,
            null
        ));

        self::assertTrue($updated);
        self::assertTrue(TimeStatus::IN_PLAY()->equals($sut->getTimeStatus()));
        self::assertEquals(0, $sut->getScoreHome());
        self::assertEquals(1, $sut->getScoreAway());
    }

    /** @dataProvider provideUpdateLinesWithNewOdds */
    public function testUpdateLines(string $lineId, $odds, $handicap, Line ...$eventOdds)
    {
        $sut = new ApiEvent();
        $sut->updatelines(...$eventOdds);

        $test = $sut->getLine($lineId);

        self::assertEquals($odds, $test->getOdds());
        self::assertEquals($handicap, $test->getHandicap());
    }

    public function provideUpdateLinesWithNewOdds()
    {
        $odds[] = new Line(
            '12345',
            200,
            new Decimal('4'),
            null,
        );

        $odds[] = new Line(
            '12346',
            -115,
            null,
            null,
        );

        $odds[] = new Line(
            '12345',
            190,
            new Decimal('4'),
            null,
        );

        return [
            ['12345', 200, new Decimal('4'), $odds[0]],
            ['12346', -115, null, $odds[0], $odds[1]],
            ['12345', 190, new Decimal('4'), $odds[0], $odds[1], $odds[2]],
        ];
    }

    /** @dataProvider provideIsBettable */
    public function testIsBettable(Result $result, ?Line $line, ?Offer $offer, bool $allowLiveBetting, bool $expected)
    {
        $sut = new ApiEvent();
        $sut->result($result);
        if ($line !== null) {
            $sut->updateLines($line);
        }

        if ($offer !== null) {
            $sut->updateOffers($offer);
        }

        self::assertEquals($expected, $sut->isBettable($allowLiveBetting));
    }

    public function provideIsBettable()
    {
        $preMatch = new Result(
            'eid',
            'test',
            TimeStatus::NOT_STARTED(),
            '2020-10-01 18:08:00',
            null,
            null,
            null,
            null
        );

        $inPlay = new Result(
            'eid',
            'test',
            TimeStatus::IN_PLAY(),
            '2020-10-01 18:08:00',
            0,
            1,
            null,
            null
        );

        $finished = new Result(
            'eid',
            'test',
            TimeStatus::ENDED(),
            '2020-10-01 18:08:00',
            1,
            3,
            null,
            null
        );

        $line = new Line('Line1', 200, null, null);
        $offer = new Offer('line1', Offer::MONEYLINE, Offer::HOME, Offer::FULL_TIME);

        return [
            [$preMatch, null, null, true, false],
            [$preMatch, $line, null, false, false],
            [$preMatch, $line, $offer, false, true],
            [$inPlay, $line, $offer, false, false],
            [$inPlay, $line, $offer, true, true],
            [$finished, $line, $offer, true, false]
        ];
    }
}
