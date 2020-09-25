<?php

namespace Unit\Domain;

use App\Betting\SportEventResult;
use App\Betting\TimeStatus;
use App\Domain\BetItem;
use App\Domain\BetTypes\MoneyLineAway;
use App\Domain\BetTypes\TotalOver;
use App\Domain\Tournament;
use App\Domain\User;
use PHPUnit\Framework\TestCase;
use Tests\Fixture\Factory\ApiEventFactory;
use Tests\Fixture\Factory\FactoryAbstract;

/**
 * @covers \App\Domain\TournamentBet
 * @uses \App\Domain\Tournament
 * @uses \App\Domain\BetItem
 * @uses \App\Betting\TimeStatus
 */
class TournamentBetTest extends TestCase
{
    /** @dataProvider provideEvaluateStraightBet */
    public function testEvaluateStraightBet($home, $away, $result, $balance)
    {
        $apiEvent = ApiEventFactory::create();
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 10000);
        $tournament->addEvent($apiEvent);
        $tournamentEvent = $tournament->getEvents()->first();

        $user = new User('test', 'test@test.com', 'test', '', '', new \DateTime());
        $tournament->registerPlayer($user);
        $player = $user->getTournamentPlayer($tournament);

        $tournament->placeStraightBet($player, 1000, new BetItem(MoneyLineAway::class, $tournamentEvent));
        $apiEvent->result(new SportEventResult('eid', 'test', TimeStatus::ENDED(), new \DateTime(), $home, $away));

        $sut = $tournament->getBets()->first();
        $bet = $sut->getEvents()->first();

        $evaluated = $bet->evaluate();

        self::assertTrue($evaluated);
        self::assertEquals($result, $sut->getStatus()->getValue());
        self::assertEquals($balance, $player->getChips());
        self::assertEquals($balance, $player->getBalance());
    }

    public function provideEvaluateStraightBet()
    {
        return [
            [0, 0, 'push', 10000],
            [1, 1, 'push', 10000],
            [1, 0, 'loss', 9000],
            [0, 1, 'win', 10500],
        ];
    }

    public function testEvaluatePendingStraightBet()
    {
        $home = null;
        $away = null;
        $result = 'pending';

        $apiEvent = ApiEventFactory::create();
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 10000);
        $tournament->addEvent($apiEvent);
        $tournamentEvent = $tournament->getEvents()->first();

        $user = new User('test', 'test@test.com', 'test', '', '', new \DateTime());
        $tournament->registerPlayer($user);
        $player = $user->getTournamentPlayer($tournament);

        $tournament->placeStraightBet($player, 1000, new BetItem(MoneyLineAway::class, $tournamentEvent));
        $apiEvent->result(new SportEventResult('eid', 'test', TimeStatus::IN_PLAY(), new \DateTime(), $home, $away));

        $sut = $tournament->getBets()->first();
        $bet = $sut->getEvents()->first();

        $evaluated = $bet->evaluate();

        self::assertFalse($evaluated);
        self::assertEquals($result, $sut->getStatus()->getValue());
        self::assertEquals(9000, $player->getChips());
        self::assertEquals(10000, $player->getBalance());
    }

    /** @dataProvider provideEvaluateParlayBet */
    public function testEvaluateParlayBet($home, $away, $result, $balance)
    {
        $apiEvent = ApiEventFactory::create();
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 10000);
        $tournament->addEvent($apiEvent);
        $tournamentEvent = $tournament->getEvents()->first();
        FactoryAbstract::setProperty($tournamentEvent, 'id', 1);

        $user = new User('test', 'test@test.com', 'test', '', '', new \DateTime());
        $tournament->registerPlayer($user);
        $player = $user->getTournamentPlayer($tournament);

        $tournament->placeParlayBet($player, 1000, new BetItem(MoneyLineAway::class, $tournamentEvent), new BetItem(TotalOver::class, $tournamentEvent));
        $apiEvent->result(new SportEventResult('eid', 'test', TimeStatus::ENDED(), new \DateTime(), $home, $away));

        $sut = $tournament->getBets()->first();
        $evaluated = false;
        foreach($sut->getEvents() as $bet) {
            $evaluated = $evaluated || $bet->evaluate();
        }

        self::assertTrue($evaluated);
        self::assertEquals($result, $sut->getStatus()->getValue());
        self::assertEquals($balance, $player->getChips());
        self::assertEquals($balance, $player->getBalance());
    }

    public function provideEvaluateParlayBet()
    {
        return [
            [0, 0, 'loss', 9000], //push, loss
            [5, 5, 'win', 11500], //push, win
            [1, 0, 'loss', 9000], //loss, loss
            [0, 1, 'loss', 9000], //loss, win
            [0, 7, 'win', 12750], //win, win
        ];
    }

    public function testEvaluateParlayBetWithPending()
    {
        $home = 0;
        $away = 1;
        $result = 'pending';

        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 10000);

        $apiEvent = ApiEventFactory::create();
        $tournament->addEvent($apiEvent);
        $apiEvent = ApiEventFactory::create();
        $tournament->addEvent($apiEvent);

        [$tournamentEvent1, $tournamentEvent2] = $tournament->getEvents()->toArray();
        FactoryAbstract::setProperty($tournamentEvent1, 'id', 1);
        FactoryAbstract::setProperty($tournamentEvent2, 'id', 2);

        $user = new User('test', 'test@test.com', 'test', '', '', new \DateTime());
        $tournament->registerPlayer($user);
        $player = $user->getTournamentPlayer($tournament);

        $tournament->placeParlayBet($player, 1000, new BetItem(MoneyLineAway::class, $tournamentEvent1), new BetItem(TotalOver::class, $tournamentEvent2));
        $apiEvent->result(new SportEventResult('eid', 'test', TimeStatus::ENDED(), new \DateTime(), $home, $away));

        $sut = $tournament->getBets()->first();
        $evaluated = true;
        foreach($sut->getEvents() as $bet) {
            $evaluated = $evaluated && $bet->evaluate();
        }

        self::assertFalse($evaluated);
        self::assertEquals($result, $sut->getStatus()->getValue());
        self::assertEquals(9000, $player->getChips());
        self::assertEquals(10000, $player->getBalance());
    }

    public function testEvaluateParlayBetWithTwoEvents()
    {
        $home = 1;
        $away = 0;
        $result = 'loss';

        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 10000);

        $apiEvent1 = ApiEventFactory::create();
        $tournament->addEvent($apiEvent1);
        $apiEvent2 = ApiEventFactory::create();
        $tournament->addEvent($apiEvent2);

        [$tournamentEvent1, $tournamentEvent2] = $tournament->getEvents()->toArray();
        FactoryAbstract::setProperty($tournamentEvent1, 'id', 1);
        FactoryAbstract::setProperty($tournamentEvent2, 'id', 2);

        $user = new User('test', 'test@test.com', 'test', '', '', new \DateTime());
        $tournament->registerPlayer($user);
        $player = $user->getTournamentPlayer($tournament);

        $tournament->placeParlayBet($player, 1000, new BetItem(MoneyLineAway::class, $tournamentEvent1), new BetItem(TotalOver::class, $tournamentEvent2));
        $apiEvent1->result(new SportEventResult('eid', 'test', TimeStatus::ENDED(), new \DateTime(), $home, $away));
        $apiEvent2->result(new SportEventResult('eid', 'test', TimeStatus::ENDED(), new \DateTime(), $home, $away));

        $sut = $tournament->getBets()->first();
        $evaluated = false;
        foreach($sut->getEvents() as $bet) {
            $evaluated = $evaluated || $bet->evaluate();
        }

        self::assertTrue($evaluated);
        self::assertEquals($result, $sut->getStatus()->getValue());
        self::assertEquals(9000, $player->getChips());
        self::assertEquals(9000, $player->getBalance());
    }
}
