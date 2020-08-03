<?php

namespace Unit\Domain\BetTypes;

use App\Betting\SportEventResult;
use App\Betting\TimeStatus;
use App\Domain\BetItem;
use App\Domain\BetTypes\TotalUnder;
use App\Domain\Tournament;
use App\Domain\TournamentPlayer;
use App\Domain\User;
use PHPUnit\Framework\TestCase;
use Tests\Fixture\Factory\ApiEventFactory;

/**
 * @covers App\Domain\BetTypes\TotalUnder
 * @uses \App\Domain\ApiEvent
 * @uses App\Betting\SportEventOdd
 * @uses App\Betting\SportEventResult
 * @uses App\Domain\ApiEvent
 * @uses App\Domain\ApiEventOdds
 * @uses App\Domain\TournamentBet
 * @uses App\Domain\BetItem
 * @uses App\Domain\Tournament
 * @uses App\Domain\TournamentBetEvent
 * @uses App\Domain\TournamentEvent
 * @uses App\Domain\TournamentPlayer
 * @uses App\Domain\User
 * @uses App\Domain\Odds
 */
class TotalUnderTest extends TestCase
{
    /** @dataProvider provideEvaluate */
    public function testEvaluate($home, $away, $result)
    {
        $apiEvent = ApiEventFactory::create();
        $tournament = new Tournament();
        $tournament->addEvent($apiEvent);
        $tournamentEvent = $tournament->getEvents()->first();

        $user = new User('test', 'test@test.com', 'test');
        $player = new TournamentPlayer($tournament, $user, 1000);

        $tournament->placeStraightBet($player, 1000, new BetItem(TotalUnder::class, $tournamentEvent));
        $apiEvent->result(new SportEventResult('eid', TimeStatus::ENDED(), $home, $away));

        $sut = $tournament->getBets()->first()->getEvents()->first();

        $sut->evaluate();

        self::assertEquals($result, $sut->getStatus()->getValue());
    }

    public function provideEvaluate()
    {
        return [
            [0, 0, 'win'],
            [3, 0, 'win'],
            [2, 2, 'push'],
            [3, 2, 'loss'],
            [0, 5, 'loss'],
        ];
    }
}
