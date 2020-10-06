<?php

namespace Unit\Domain\Prizes;

use App\Domain\Prizes\PrizeMoney;
use App\Domain\Prizes\PrizeMoneyCollection;
use App\Domain\Tournament;
use App\Domain\TournamentPlayer;
use App\Domain\User;
use PHPUnit\Framework\TestCase;
use Tests\Fixture\Factory\FactoryAbstract;

/**
 * @covers \App\Domain\Prizes\PrizeMoneyCollection
 * @uses \App\Domain\Prizes\PrizeMoney
 * @uses \App\Domain\TournamentPlayer
 * @uses \App\Domain\User
 * @uses \App\Domain\TournamentPayout
 */
class PrizeMoneyCollectionTest extends TestCase
{
    public function testConstruct()
    {
        $prizes = [
            new PrizeMoney(1, 2000),
            new PrizeMoney(5, 1000),
            new PrizeMoney(10, 500),
        ];

        $sut = new PrizeMoneyCollection(...$prizes);

        self::assertCount(3, $sut->toArray());
    }

    /** @dataProvider provideAllocate */
    public function testAllocate(array $prizes, array $expected, int $payoutsExpected)
    {
        $sut = new PrizeMoneyCollection(...$prizes);

        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 10000);
        $players = [];

        for ($i = 0; $i < 10; $i++) {
            $user = new User('test' . $i,  'test@test.com', 'test', '', '', new \DateTime());
            FactoryAbstract::setProperty($user, 'id', $i);
            $tournament->registerPlayer($user);
            $players[$i] = $user->getTournamentPlayer($tournament);
            FactoryAbstract::setProperty($players[$i], 'chips', 1000 * $i);
        }

        $payouts = $sut->allocate(...$players);

        $result = array_map(fn (TournamentPlayer $tournamentPlayer) => [$tournamentPlayer->getUser()->getName(), $tournamentPlayer->getUser()->getBalance()], $players);

        self::assertEquals($expected, $result);
        self::assertCount($payoutsExpected, $payouts);
    }

    public function provideAllocate()
    {
        return [
            [
                [
                    new PrizeMoney(1, 2000),
                    new PrizeMoney(2, 1000),
                    new PrizeMoney(3, 500),
                ],
                [
                    ['test0', 0],
                    ['test1', 0],
                    ['test2', 0],
                    ['test3', 0],
                    ['test4', 0],
                    ['test5', 0],
                    ['test6', 0],
                    ['test7', 500],
                    ['test8', 1000],
                    ['test9', 2000],
                ],
                3
            ],
            [
                [
                    new PrizeMoney(1, 2000),
                    new PrizeMoney(2, 1000),
                    new PrizeMoney(5, 500),
                ],
                [
                    ['test0', 0],
                    ['test1', 0],
                    ['test2', 0],
                    ['test3', 0],
                    ['test4', 0],
                    ['test5', 500],
                    ['test6', 500],
                    ['test7', 500],
                    ['test8', 1000],
                    ['test9', 2000],
                ],
                5
            ]
        ];
    }
}
