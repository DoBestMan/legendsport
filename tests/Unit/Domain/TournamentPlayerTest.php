<?php

namespace Unit\Domain;

use App\Domain\BetPlacementException;
use App\Domain\Tournament;
use App\Domain\User;
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
        $user = new User('player 1', 'player1@test.com', '...');
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

    public function testAddChips()
    {
        $user = new User('player 1', 'player1@test.com', '...');
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        $tournament->registerPlayer($user);

        $sut = $user->getTournamentPlayer($tournament);

        $sut->addChips(100);

        self::assertEquals(100, $sut->getChips());
    }

    public function testReduceChipsInsufficientChips()
    {
        $user = new User('player 1', 'player1@test.com', '...');
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        $tournament->registerPlayer($user);

        $sut = $user->getTournamentPlayer($tournament);

        $this->expectException(BetPlacementException::class);
        $this->expectExceptionMessage(BetPlacementException::notEnoughChips()->getMessage());
        $sut->reduceChips(100);
    }

    public function testReduceChips()
    {
        $user = new User('player 1', 'player1@test.com', '...');
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        $tournament->registerPlayer($user);

        $sut = $user->getTournamentPlayer($tournament);

        $sut->addChips(200);
        $sut->reduceChips(150);

        self::assertEquals(50, $sut->getChips());
    }
}
