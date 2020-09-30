<?php

namespace Unit\Domain;

use App\Domain\User;
use App\Domain\UserBalanceException;
use PHPUnit\Framework\TestCase;
use Tests\Fixture\Factory\FactoryAbstract;

/**
 * @covers \App\Domain\User
 * @covers \App\Domain\UserBalanceException
 */
class UserTest extends TestCase
{
    public function testMakeWithdrawalNoBalance()
    {
        $sut = new User('User1', 'test@test.com', '...', 'First', 'Last', new \DateTime());

        $this->expectException(UserBalanceException::class);
        $this->expectExceptionMessage(UserBalanceException::insufficientBalanceToMakeWithdrawal(50, 0)->getMessage());

        $sut->makeWithdrawal('BTCAddress', 50);
    }

    public function testMakeWithdrawal()
    {
        $sut = new User('User1', 'test@test.com', '...', 'First', 'Last', new \DateTime());
        FactoryAbstract::setProperty($sut, 'balance', 5000);

        $sut->makeWithdrawal('BTCAddress', 5000);

        self::assertEquals(0, $sut->getBalance());
        self::assertCount(1, $sut->getWithdrawals()->toArray());
    }
}
