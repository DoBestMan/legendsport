<?php

namespace Unit\Domain;

use App\Betting\TimeStatus;
use App\Domain\BetItem;
use App\Domain\BetPlacementException;
use App\Domain\BetTypes\MoneyLineAway;
use App\Domain\BetTypes\SpreadAway;
use App\Domain\BetTypes\TotalOver;
use App\Domain\Tournament;
use App\Domain\TournamentBet;
use App\Domain\TournamentPlayer;
use App\Domain\User;
use App\Tournament\Enums\TournamentState;
use PHPUnit\Framework\TestCase;
use Tests\Fixture\Factory\ApiEventFactory;
use Tests\Fixture\Factory\FactoryAbstract;

/**
 * @covers \App\Domain\Tournament
 * @covers \App\Domain\BetPlacementException
 * @uses \App\Domain\User
 * @uses \App\Domain\TournamentPlayer
 * @uses \App\Domain\BetItem
 */
class TournamentTest extends TestCase
{
    public function testPlaceStraightBet()
    {
        $apiEvent = ApiEventFactory::create();
        $user = new User('test', 'test@test.com', 'test', '', '', new \DateTime());
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
        self::assertEquals(9500, $player->getChips());
        self::assertEquals(10000, $player->getBalance());
    }

    public function testPlaceStraightBetInTournamentWithStartedEvents()
    {
        $user = new User('test', 'test@test.com', 'test', '', '', new \DateTime());
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 10000);
        FactoryAbstract::setProperty($tournament, 'state', TournamentState::RUNNING());
        $tournament->registerPlayer($user);
        $tournament->addEvent(ApiEventFactory::createStartedEvent());
        $tournament->addEvent(ApiEventFactory::create());
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

    public function testPlaceStraightBetNotRegistered()
    {
        $apiEvent = ApiEventFactory::create();
        $user = new User('test', 'test@test.com', 'test', '', '', new \DateTime());
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 10000);
        $tournament->addEvent($apiEvent);
        $event = $tournament->getBettableEvents()->first();
        $player = new TournamentPlayer($tournament, $user, 10000);

        $this->expectException(BetPlacementException::class);
        $this->expectExceptionMessage(BetPlacementException::notRegistered()->getMessage());
        $tournament->placeStraightBet($player, 500, new BetItem(MoneyLineAway::class, $event));
    }

    public function testPlaceStraightBetTournamentOver()
    {
        $apiEvent = ApiEventFactory::create();
        $user = new User('test', 'test@test.com', 'test', '', '', new \DateTime());
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

        $user = new User('test', 'test@test.com', 'test', '', '', new \DateTime());
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 10000);
        $tournament->registerPlayer($user);
        $tournament->addEvent($apiEvent);
        $event = $tournament->getBettableEvents()->first();
        $player = $user->getTournamentPlayer($tournament);

        FactoryAbstract::setProperty($apiEvent, 'timeStatus', TimeStatus::ENDED());
        $this->expectException(BetPlacementException::class);
        $this->expectExceptionMessage(BetPlacementException::eventStarted()->getMessage());
        $tournament->placeStraightBet($player, 500, new BetItem(MoneyLineAway::class, $event));
    }

    public function testPlaceStraightBetInvalidEvent()
    {
        $apiEvent = ApiEventFactory::create();
        $user = new User('test', 'test@test.com', 'test', '', '', new \DateTime());
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

    public function testPlaceParlayBet()
    {
        $user = new User('test', 'test@test.com', 'test', '', '', new \DateTime());
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 10000);
        $tournament->registerPlayer($user);
        $tournament->addEvent(ApiEventFactory::create());
        $event = $tournament->getBettableEvents()->first();
        FactoryAbstract::setProperty($event, 'id', 1);

        $player = $user->getTournamentPlayer($tournament);

        $tournament->placeParlayBet(
            $player, 500,
            new BetItem(MoneyLineAway::class, $event),
            new BetItem(TotalOver::class, $event)
        );

        self::assertEquals(1, $tournament->getBets()->count());

        /** @var TournamentBet $bet */
        $bet = $tournament->getBets()->first();

        self::assertEquals(500, $bet->getChipsWager());
        self::assertSame($player, $bet->getTournamentPlayer());
        self::assertEquals(2, $bet->getEvents()->count());
        self::assertEquals(9500, $player->getChips());
        self::assertEquals(10000, $player->getBalance());
    }

    public function testPlaceParlayBetInTournamentWithStartedEvent()
    {
        $user = new User('test', 'test@test.com', 'test', '', '', new \DateTime());
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 10000);
        FactoryAbstract::setProperty($tournament, 'state', TournamentState::RUNNING());
        $tournament->registerPlayer($user);
        $tournament->addEvent(ApiEventFactory::create());
        $tournament->addEvent(ApiEventFactory::createStartedEvent());
        $event = $tournament->getBettableEvents()->first();
        FactoryAbstract::setProperty($event, 'id', 1);

        $player = $user->getTournamentPlayer($tournament);

        $tournament->placeParlayBet(
            $player, 500,
            new BetItem(MoneyLineAway::class, $event),
            new BetItem(TotalOver::class, $event)
        );

        self::assertEquals(1, $tournament->getBets()->count());

        /** @var TournamentBet $bet */
        $bet = $tournament->getBets()->first();

        self::assertEquals(500, $bet->getChipsWager());
        self::assertSame($player, $bet->getTournamentPlayer());
        self::assertEquals(2, $bet->getEvents()->count());
    }

    public function testPlaceParlayBetNotRegistered()
    {
        $apiEvent = ApiEventFactory::create();
        $user = new User('test', 'test@test.com', 'test', '', '', new \DateTime());
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 10000);
        $tournament->addEvent($apiEvent);
        $event = $tournament->getBettableEvents()->first();
        $player = new TournamentPlayer($tournament, $user, 10000);

        $this->expectException(BetPlacementException::class);
        $this->expectExceptionMessage(BetPlacementException::notRegistered()->getMessage());
        $tournament->placeParlayBet(
            $player, 500,
            new BetItem(MoneyLineAway::class, $event),
            new BetItem(TotalOver::class, $event)
        );
    }

    public function testPlaceParlayBetTournamentOver()
    {
        $apiEvent = ApiEventFactory::create();
        $user = new User('test', 'test@test.com', 'test', '', '', new \DateTime());
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
        $tournament->placeParlayBet(
            $player, 500,
            new BetItem(MoneyLineAway::class, $event),
            new BetItem(TotalOver::class, $event)
        );
    }

    public function testPlaceParlayBetEventStarted()
    {
        $apiEvent = ApiEventFactory::create();

        $user = new User('test', 'test@test.com', 'test', '', '', new \DateTime());
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 10000);
        $tournament->registerPlayer($user);
        $tournament->addEvent($apiEvent);
        $event = $tournament->getBettableEvents()->first();
        $player = $user->getTournamentPlayer($tournament);

        FactoryAbstract::setProperty($apiEvent, 'timeStatus', TimeStatus::ENDED());
        $this->expectException(BetPlacementException::class);
        $this->expectExceptionMessage(BetPlacementException::eventStarted()->getMessage());
        $tournament->placeParlayBet(
            $player, 500,
            new BetItem(MoneyLineAway::class, $event),
            new BetItem(TotalOver::class, $event)
        );
    }

    public function testPlaceParlayBetInvalidEvent()
    {
        $apiEvent = ApiEventFactory::create();
        $user = new User('test', 'test@test.com', 'test', '', '', new \DateTime());
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 10000);
        $tournament->registerPlayer($user);
        $tournament->addEvent($apiEvent);
        $event = $tournament->getBettableEvents()->first();
        $player = $user->getTournamentPlayer($tournament);

        $this->expectException(BetPlacementException::class);
        $this->expectExceptionMessage(BetPlacementException::invalidEvent()->getMessage());
        $tournament->placeParlayBet(
            $player, 500,
            new BetItem(MoneyLineAway::class, clone $event),
            new BetItem(TotalOver::class, $event)
        );
    }

    public function testPlaceParlayBetCorrelatedEvents()
    {
        $apiEvent = ApiEventFactory::create();
        $user = new User('test', 'test@test.com', 'test', '', '', new \DateTime());
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 10000);
        $tournament->registerPlayer($user);
        $tournament->addEvent($apiEvent);
        $event = $tournament->getBettableEvents()->first();
        FactoryAbstract::setProperty($event, 'id', 1);
        $player = $user->getTournamentPlayer($tournament);

        $this->expectException(BetPlacementException::class);
        $this->expectExceptionMessage(BetPlacementException::correlatedEvents()->getMessage());
        $tournament->placeParlayBet(
            $player, 500,
            new BetItem(MoneyLineAway::class, $event),
            new BetItem(SpreadAway::class, $event)
        );
    }

    public function testPlaceParlayBetInsufficientEvents()
    {
        $apiEvent = ApiEventFactory::create();
        $user = new User('test', 'test@test.com', 'test', '', '', new \DateTime());
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 10000);
        $tournament->registerPlayer($user);
        $tournament->addEvent($apiEvent);
        $event = $tournament->getBettableEvents()->first();
        $player = $user->getTournamentPlayer($tournament);

        $this->expectException(BetPlacementException::class);
        $this->expectExceptionMessage(BetPlacementException::insufficientEvents()->getMessage());
        $tournament->placeParlayBet(
            $player, 500,
            new BetItem(MoneyLineAway::class, $event)
        );
    }
}
