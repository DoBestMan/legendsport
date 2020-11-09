<?php

namespace Unit\Domain;

use App\Domain\BetPlacementException;
use App\Domain\Tournament;
use App\Domain\TournamentBet;
use App\Domain\User;
use App\Tournament\Enums\BetStatus;
use PHPUnit\Framework\TestCase;
use Tests\Fixture\Factory\FactoryAbstract;

/**
 * @covers \App\Domain\TournamentPlayer
 * @covers \App\Domain\BetPlacementException
 * @uses \App\Domain\Tournament
 * @uses \App\Domain\User
 */
class TournamentPlayerTest extends TestCase
{
    public function testConstruction()
    {
        $user = new User('test', 'test@test.com', 'test', '', '', new \DateTime());
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 10000);
        $tournament->registerPlayer($user);

        $sut = $user->getTournamentPlayer($tournament);

        self::assertEquals($tournament, $sut->getTournament());
        self::assertEquals($user, $sut->getUser());
        self::assertEquals(10000, $sut->getChips());
        self::assertInstanceOf(\DateTime::class, $sut->getCreatedAt());
        self::assertInstanceOf(\DateTime::class, $sut->getUpdatedAt());

        FactoryAbstract::setProperty($sut, 'id', 1);

        self::assertEquals(1, $sut->getId());
    }

    public function testReduceChipsInsufficientChips()
    {
        $user = new User('test', 'test@test.com', 'test', '', '', new \DateTime());
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 100);
        $tournament->registerPlayer($user);

        $sut = $user->getTournamentPlayer($tournament);

        $this->expectException(BetPlacementException::class);
        $this->expectExceptionMessage(BetPlacementException::notEnoughChips()->getMessage());
        $sut->placeWager(200);
    }

    public function testPlaceWager()
    {
        $user = new User('test', 'test@test.com', 'test', '', '', new \DateTime());
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 200);
        $tournament->registerPlayer($user);

        $sut = $user->getTournamentPlayer($tournament);

        $sut->placeWager(150);

        self::assertEquals(50, $sut->getChips());
    }

    public function testBetWon()
    {
        $user = new User('test', 'test@test.com', 'test', '', '', new \DateTime());
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 50);
        $tournament->registerPlayer($user);

        $sut = $user->getTournamentPlayer($tournament);

        $bet = $this->createMock(TournamentBet::class);
        $bet->expects($this->any())->method('getStatus')->willReturn(BetStatus::WIN());
        $bet->expects($this->any())->method('getChipsWager')->willReturn(50);
        $bet->expects($this->any())->method('getChipsWon')->willReturn(500);

        $sut->betPlaced($bet);

        $sut->placeWager(50);
        $sut->betWon(50, 500);

        self::assertEquals(550, $sut->getChips());
    }

    public function testBetLost()
    {
        $user = new User('test', 'test@test.com', 'test', '', '', new \DateTime());
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 50);
        $tournament->registerPlayer($user);

        $sut = $user->getTournamentPlayer($tournament);

        $bet = $this->createMock(TournamentBet::class);
        $bet->expects($this->any())->method('getStatus')->willReturn(BetStatus::LOSS());
        $bet->expects($this->any())->method('getChipsWager')->willReturn(50);
        $bet->expects($this->any())->method('getChipsWon')->willReturn(500);

        $sut->betPlaced($bet);

        $sut->placeWager(50);
        $sut->betLost(50);

        self::assertEquals(0, $sut->getChips());
    }

    public function testBetPush()
    {
        $user = new User('test', 'test@test.com', 'test', '', '', new \DateTime());
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 50);
        $tournament->registerPlayer($user);

        $sut = $user->getTournamentPlayer($tournament);

        $sut->placeWager(50);
        $sut->betPush(50);

        self::assertEquals(50, $sut->getChips());
    }

    public function testSortedBetsByWin()
    {
        $user = new User('test', 'test@test.com', 'test', '', '', new \DateTime());
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 500);
        $tournament->registerPlayer($user);

        $sut = $user->getTournamentPlayer($tournament);

        $bet_won = $this->createMock(TournamentBet::class);
        $bet_won->expects($this->any())->method('getStatus')->willReturn(BetStatus::WIN());
        $bet_won->expects($this->any())->method('getChipsWager')->willReturn(50);
        $bet_won->expects($this->any())->method('getChipsWon')->willReturn(100);
        $bet_won->expects($this->any())->method('getActualChipsWon')->willReturn(100);

        $bet_lost = $this->createMock(TournamentBet::class);
        $bet_lost->expects($this->any())->method('getStatus')->willReturn(BetStatus::LOSS());
        $bet_lost->expects($this->any())->method('getChipsWager')->willReturn(150);
        $bet_lost->expects($this->any())->method('getChipsWon')->willReturn(300);
        $bet_lost->expects($this->any())->method('getActualChipsWon')->willReturn(-150);

        $bet_push = $this->createMock(TournamentBet::class);
        $bet_push->expects($this->any())->method('getStatus')->willReturn(BetStatus::PUSH());
        $bet_push->expects($this->any())->method('getChipsWager')->willReturn(100);
        $bet_push->expects($this->any())->method('getActualChipsWon')->willReturn(0);

        $sut->betPlaced($bet_lost);
        $sut->betPlaced($bet_won);
        $sut->betPlaced($bet_push);

        $bets = $sut->getSortedBetsByWin();
        $i = -1;
        foreach ($bets as $betItem) {
            $i++;
            switch($i) {
                case 0: 
                    self::assertEquals(BetStatus::WIN(), $betItem->getStatus());
                    break;
                case 1:
                    self::assertEquals(BetStatus::PUSH(), $betItem->getStatus());
                    break;
                case 2:
                    self::assertEquals(BetStatus::LOSS(), $betItem->getStatus());
                    break;
            }
        } 
    }
}
