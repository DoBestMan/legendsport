<?php

namespace Unit\Domain;

use App\Betting\SportEventOdd;
use App\Domain\ApiEvent;
use App\Domain\BetTypes\MoneyLineAway;
use App\Domain\BetTypes\MoneyLineHome;
use App\Domain\BetTypes\SpreadAway;
use App\Domain\BetTypes\SpreadHome;
use App\Domain\BetTypes\TotalOver;
use App\Domain\BetTypes\TotalUnder;
use Decimal\Decimal;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Domain\ApiEvent
 * @uses \App\Domain\ApiEventOdds
 * @uses \App\Betting\SportEventOdd
 */
class ApiEventTest extends TestCase
{
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
}
