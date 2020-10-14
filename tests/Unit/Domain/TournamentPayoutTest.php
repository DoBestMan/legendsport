<?php

namespace Unit\Domain;

use App\Domain\Tournament;
use App\Domain\TournamentPayout;
use App\Domain\User;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Tests\Fixture\Factory\FactoryAbstract;

/**
 * @covers \App\Domain\TournamentPayout
 * @uses \App\Domain\Tournament
 * @uses \App\Domain\User
 */
class TournamentPayoutTest extends TestCase
{
    public function testConstruct()
    {
        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        $user = new User('test',  'test@test.com', 'test', '', '', new \DateTime());
        FactoryAbstract::setProperty($user, 'id', 10);

        $sut = new TournamentPayout($tournament, $user, 1, 15000);
        FactoryAbstract::setProperty($sut, 'id', 100);

        self::assertEquals(1, $sut->getTournamentId());
        self::assertEquals(10, $sut->getUserId());
        self::assertEquals(100, $sut->getId());
        self::assertEquals($tournament, $sut->getTournament());
        self::assertEquals($user, $sut->getUser());
        self::assertFalse($sut->isBot());
        self::assertEquals(1, $sut->getRank());
        self::assertEquals(15000, $sut->getPayout());
        self::assertEqualsWithDelta(Carbon::now(), $sut->getPaidDate(), 2);
    }
}
