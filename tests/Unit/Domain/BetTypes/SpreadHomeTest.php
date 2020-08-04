<?php

namespace Unit\Domain\BetTypes;

use App\Betting\SportEventResult;
use App\Betting\TimeStatus;
use App\Domain\BetItem;
use App\Domain\BetTypes\SpreadHome;
use App\Domain\Tournament;
use App\Domain\TournamentPlayer;
use App\Domain\User;
use PHPUnit\Framework\TestCase;
use Tests\Fixture\Factory\ApiEventFactory;
use Tests\Fixture\Factory\FactoryAbstract;

/**
 * @covers App\Domain\BetTypes\SpreadHome
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
class SpreadHomeTest extends TestCase
{
    /** @dataProvider provideEvaluate */
    public function testEvaluate($home, $away, $result)
    {
        $apiEvent = ApiEventFactory::create();
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 10000);
        $tournament->addEvent($apiEvent);
        $tournamentEvent = $tournament->getEvents()->first();

        $user = new User('test', 'test@test.com', 'test');
        $tournament->registerPlayer($user);
        $player = $user->getTournamentPlayer($tournament);

        $tournament->placeStraightBet($player, 1000, new BetItem(SpreadHome::class, $tournamentEvent));
        $apiEvent->result(new SportEventResult('eid', TimeStatus::ENDED(), $home, $away));

        $sut = $tournament->getBets()->first()->getEvents()->first();

        $sut->evaluate();

        self::assertEquals($result, $sut->getStatus()->getValue());
    }

    public function provideEvaluate()
    {
        return [
            [0, 0, 'win'],
            [0, 3, 'loss'],
            [0, 1, 'win'],
            [0, 2, 'push'],
            [1, 0, 'win'],
        ];
    }
}
