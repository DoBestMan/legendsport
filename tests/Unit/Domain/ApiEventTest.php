<?php

namespace Unit\Domain;

use App\Betting\SportEventOdd;
use App\Betting\SportEventResult;
use App\Betting\TimeStatus;
use App\Domain\ApiEvent;
use App\Domain\BetTypes\MoneyLineAway;
use App\Domain\BetTypes\MoneyLineHome;
use App\Domain\BetTypes\SpreadAway;
use App\Domain\BetTypes\SpreadHome;
use App\Domain\BetTypes\TotalOver;
use App\Domain\BetTypes\TotalUnder;
use Carbon\Carbon;
use Decimal\Decimal;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Domain\ApiEvent
 * @uses \App\Domain\ApiEventOdds
 * @uses \App\Betting\SportEventOdd
 */
class ApiEventTest extends TestCase
{
    public function testResult()
    {
        $sut = new ApiEvent();
        $updated = $sut->result(new SportEventResult(
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
        $sut->result(new SportEventResult(
            'eid',
            'test',
            TimeStatus::NOT_STARTED(),
            '2020-10-01 18:08:00',
            null,
            null,
            null,
            null
        ));

        $updated = $sut->result(new SportEventResult(
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
        $sut->result(new SportEventResult(
            'eid',
            'test',
            TimeStatus::NOT_STARTED(),
            '2020-10-01 18:08:00',
            null,
            null,
            null,
            null
        ));

        $updated = $sut->result(new SportEventResult(
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

    /** @dataProvider provideUpdateOddsWithNewOdds */
    public function testUpdateOddsWithNewOdds(SportEventOdd $eventOdds, string $oddsType, $odds, $handicap)
    {
        $sut = new ApiEvent();
        $sut->updateOdds($eventOdds);

        $test = $sut->getOdds($oddsType);

        self::assertEquals($odds, $test->getOdds());
        self::assertEquals($handicap, $test->getHandicap());
    }

    public function provideUpdateOddsWithNewOdds()
    {
        $odds = new SportEventOdd(
            '12345',
            200,
            -200,
            275,
            -125,
            new Decimal('2'),
            new Decimal('-2'),
            150,
            -175,
            new Decimal('4')
        );

        return [
            [$odds, MoneyLineAway::class, -200, null],
            [$odds, MoneyLineHome::class, 200, null],
            [$odds, SpreadAway::class, -125, new Decimal('2')],
            [$odds, SpreadHome::class, 275, new Decimal('-2')],
            [$odds, TotalOver::class, 150, new Decimal('4')],
            [$odds, TotalUnder::class, -175, new Decimal('4')],
        ];
    }

    /** @dataProvider provideUpdateOdds */
    public function testUpdateOdds(SportEventOdd $eventOdds, SportEventOdd $newOdds, string $oddsType, $odds, $handicap)
    {
        $sut = new ApiEvent();
        $sut->updateOdds($eventOdds);
        $sut->updateOdds($newOdds);

        $test = $sut->getOdds($oddsType);

        self::assertEquals($odds, $test->getOdds());
        self::assertEquals($handicap, $test->getHandicap());
    }

    public function provideUpdateOdds()
    {
        $oldOdds = new SportEventOdd(
            '12345',
            200,
            -200,
            275,
            -125,
            new Decimal('2'),
            new Decimal('-2'),
            150,
            -175,
            new Decimal('4')
        );

        $newOdds = new SportEventOdd(
            '12345',
            300,
            -100,
            175,
            -175,
            new Decimal('2.5'),
            new Decimal('-2.5'),
            120,
            -145,
            new Decimal('4.5')
        );

        return [
            [$oldOdds, $newOdds, MoneyLineAway::class, -100, null],
            [$oldOdds, $newOdds, MoneyLineHome::class, 300, null],
            [$oldOdds, $newOdds, SpreadAway::class, -175, new Decimal('2.5')],
            [$oldOdds, $newOdds, SpreadHome::class, 175, new Decimal('-2.5')],
            [$oldOdds, $newOdds, TotalOver::class, 120, new Decimal('4.5')],
            [$oldOdds, $newOdds, TotalUnder::class, -145, new Decimal('4.5')],
        ];
    }

    /** @dataProvider provideIsBettable */
    public function testIsBettable(SportEventResult $result, ?SportEventOdd $odds, bool $allowLiveBetting, bool $expected)
    {
        $sut = new ApiEvent();
        $sut->result($result);
        if ($odds !== null) {
            $sut->updateOdds($odds);
        }

        self::assertEquals($expected, $sut->isBettable($allowLiveBetting));
    }

    public function provideIsBettable()
    {
        $preMatch = new SportEventResult(
            'eid',
            'test',
            TimeStatus::NOT_STARTED(),
            '2020-10-01 18:08:00',
            null,
            null,
            null,
            null
        );

        $inPlay = new SportEventResult(
            'eid',
            'test',
            TimeStatus::IN_PLAY(),
            '2020-10-01 18:08:00',
            0,
            1,
            null,
            null
        );

        $finished = new SportEventResult(
            'eid',
            'test',
            TimeStatus::ENDED(),
            '2020-10-01 18:08:00',
            1,
            3,
            null,
            null
        );

        $odds = new SportEventOdd(
            '12345',
            200,
            -200,
            275,
            -125,
            new Decimal('2'),
            new Decimal('-2'),
            150,
            -175,
            new Decimal('4')
        );

        return [
            [$preMatch, null, true, false],
            [$preMatch, $odds, false, true],
            [$inPlay, $odds, false, false],
            [$inPlay, $odds, true, true],
            [$finished, $odds, true, false]
        ];
    }
}
