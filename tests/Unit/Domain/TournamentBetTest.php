<?php

namespace Unit\Domain;

use App\Betting\Settlement;
use App\Betting\SportEvent\Line;
use App\Betting\SportEvent\Result;
use App\Betting\TimeStatus;
use App\Domain\BetItem;
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
    public function testEvaluateStraightBet($line, $result, $balance)
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

        $tournament->placeStraightBet($player, 1000, new BetItem('moneyline_away', $tournamentEvent));
        $apiEvent->updateLines($line);

        $sut = $tournament->getBets()->first();
        $bet = $sut->getEvents()->first();

        $evaluated = $bet->evaluate();

        self::assertTrue($evaluated);
        self::assertEquals($result, $sut->getStatus()->getValue());
        self::assertEquals($balance, $player->getChips());
    }

    public function provideEvaluateStraightBet()
    {
        $win = new Line('moneyline::away::fulltime', 200, null, Settlement::WON());
        $loss = new Line('moneyline::away::fulltime', 200, null, Settlement::LOST());
        $push = new Line('moneyline::away::fulltime', 200, null, Settlement::PUSH());
        return [
            [$push, 'push', 10000],
            [$push, 'push', 10000],
            [$loss, 'loss', 9000],
            [$win, 'win', 10500],
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

        $tournament->placeStraightBet($player, 1000, new BetItem('moneyline_away', $tournamentEvent));
        $apiEvent->result(new Result('eid', 'test', TimeStatus::IN_PLAY(), new \DateTime(), $home, $away));

        $sut = $tournament->getBets()->first();
        $bet = $sut->getEvents()->first();

        $evaluated = $bet->evaluate();

        self::assertFalse($evaluated);
        self::assertEquals($result, $sut->getStatus()->getValue());
        self::assertEquals(9000, $player->getChips());
    }

    /** @dataProvider provideEvaluateParlayBet */
    public function testEvaluateParlayBet($moneylineLine, $totalLine, $result, $balance)
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

        $tournament->placeParlayBet($player, 1000, new BetItem('moneyline_away', $tournamentEvent), new BetItem('total_over', $tournamentEvent));
        $apiEvent->updateLines($moneylineLine, $totalLine);

        $sut = $tournament->getBets()->first();
        $evaluated = false;
        foreach($sut->getEvents() as $bet) {
            $evaluated = $evaluated || $bet->evaluate();
        }

        self::assertTrue($evaluated);
        self::assertEquals($result, $sut->getStatus()->getValue());
        self::assertEquals($balance, $player->getChips());
    }

    public function provideEvaluateParlayBet()
    {
        $winml = new Line('moneyline::away::fulltime', 200, null, Settlement::WON());
        $lossml = new Line('moneyline::away::fulltime', 200, null, Settlement::LOST());
        $pushml = new Line('moneyline::away::fulltime', 200, null, Settlement::PUSH());
        $winto = new Line('total::over::fulltime', 200, null, Settlement::WON());
        $lossto = new Line('total::over::fulltime', 200, null, Settlement::LOST());

        return [
            [$pushml, $lossto, 'loss', 9000], //push, loss
            [$pushml, $winto, 'win', 11500], //push, win
            [$lossml, $lossto, 'loss', 9000], //loss, loss
            [$lossml, $winto , 'loss', 9000], //loss, win
            [$winml, $winto , 'win', 12750], //win, win
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

        $tournament->placeParlayBet($player, 1000, new BetItem('moneyline_away', $tournamentEvent1), new BetItem('total_over', $tournamentEvent2));
        $apiEvent->result(new Result('eid', 'test', TimeStatus::ENDED(), new \DateTime(), $home, $away));

        $sut = $tournament->getBets()->first();
        $evaluated = true;
        foreach($sut->getEvents() as $bet) {
            $evaluated = $evaluated && $bet->evaluate();
        }

        self::assertFalse($evaluated);
        self::assertEquals($result, $sut->getStatus()->getValue());
        self::assertEquals(9000, $player->getChips());
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

        $tournament->placeParlayBet($player, 1000, new BetItem('moneyline_away', $tournamentEvent1), new BetItem('total_over', $tournamentEvent2));

        $lossml = new Line('moneyline::away::fulltime', 200, null, Settlement::LOST());
        $lossto = new Line('total::over::fulltime', 200, null, Settlement::LOST());
        $apiEvent1->updateLines($lossml);
        $apiEvent2->updateLines($lossto);

        $sut = $tournament->getBets()->first();
        $evaluated = false;
        foreach($sut->getEvents() as $bet) {
            $evaluated = $evaluated || $bet->evaluate();
        }

        self::assertTrue($evaluated);
        self::assertEquals($result, $sut->getStatus()->getValue());
        self::assertEquals(9000, $player->getChips());
    }
}
