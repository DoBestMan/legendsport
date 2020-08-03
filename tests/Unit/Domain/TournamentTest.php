<?php

namespace Unit\Domain;

use App\Domain\BetItem;
use App\Domain\BetPlacementException;
use App\Domain\BetTypes\MoneyLineAway;
use App\Domain\Tournament;
use App\Domain\TournamentBet;
use App\Domain\User;
use App\Tournament\Enums\TournamentState;
use PHPUnit\Framework\TestCase;
use Tests\Fixture\Factory\ApiEventFactory;
use Tests\Fixture\Factory\FactoryAbstract;

/**
 * @covers \App\Domain\Tournament
 * @uses \App\Domain\User
 * @uses \App\Domain\TournamentPlayer
 * @uses \App\Domain\BetItem
 */
class TournamentTest extends TestCase
{
    public function testPlaceStraightBet()
    {
        $apiEvent = ApiEventFactory::create();
        $user = new User('player 1', 'player1@test.com', '...');
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 10000);
        $tournament->registerPlayer($user);
        $tournament->addEvent($apiEvent);
        $event = $tournament->getBettableEvents()->first();

        $player = $user->getTournamentPlayer($tournament);

        $tournament->placeStraightBet($player, 500, new BetItem(MoneyLineAway::class, $event));

        self::assertEquals(1, $tournament->getBets()->count());

        /** @var TournamentBet $bet */
        $bet = $tournament->getBets()->first();

        self::assertEquals(500, $bet->getChipsWager());
        self::assertSame($player, $bet->getTournamentPlayer());
        self::assertEquals(1, $bet->getEvents()->count());
    }

    public function testPlaceStraightBetTournamentOver()
    {
        $apiEvent = ApiEventFactory::create();
        $user = new User('player 1', 'player1@test.com', '...');
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 10000);
        $tournament->registerPlayer($user);
        $tournament->addEvent($apiEvent);
        $event = $tournament->getBettableEvents()->first();
        $player = $user->getTournamentPlayer($tournament);

        FactoryAbstract::setProperty($tournament, 'state', TournamentState::COMPLETED());
        $this->expectException(BetPlacementException::class);
        $this->expectExceptionMessage(BetPlacementException::tournamentOver()->getMessage());
        $tournament->placeStraightBet($player, 500, new BetItem(MoneyLineAway::class, $event));
    }

    public function testPlaceStraightBetEventStarted()
    {
        $apiEvent = ApiEventFactory::create();

        $user = new User('player 1', 'player1@test.com', '...');
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 10000);
        $tournament->registerPlayer($user);
        $tournament->addEvent($apiEvent);
        $event = $tournament->getBettableEvents()->first();
        $player = $user->getTournamentPlayer($tournament);

        FactoryAbstract::setProperty($apiEvent, 'timeStatus', 'ended');
        $this->expectException(BetPlacementException::class);
        $this->expectExceptionMessage(BetPlacementException::eventStarted()->getMessage());
        $tournament->placeStraightBet($player, 500, new BetItem(MoneyLineAway::class, $event));
    }

    public function testPlaceStraightBetInvalidEvent()
    {
        $apiEvent = ApiEventFactory::create();
        $user = new User('player 1', 'player1@test.com', '...');
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 10000);
        $tournament->registerPlayer($user);
        $tournament->addEvent($apiEvent);
        $event = $tournament->getBettableEvents()->first();
        $player = $user->getTournamentPlayer($tournament);

        $this->expectException(BetPlacementException::class);
        $this->expectExceptionMessage(BetPlacementException::invalidEvent()->getMessage());
        $tournament->placeStraightBet($player, 500, new BetItem(MoneyLineAway::class, clone $event));

    }
}
