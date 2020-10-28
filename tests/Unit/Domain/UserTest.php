<?php

namespace Unit\Domain;

use App\Domain\User;
use App\Domain\UserBalanceException;
use Carbon\Carbon;
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

    public function testUpdatePassword()
    {
        $sut = new User('User1', 'test@test.com', '...', 'First', 'Last', new \DateTime());
        $sut->updatePassword('$2y$dkjfhsdkf$akdfshdkfjhskdfjhskdjfhskdjf');

        self::assertEquals('$2y$dkjfhsdkf$akdfshdkfjhskdfjhskdjfhskdjf', $sut->getPassword());
        self::assertEqualsWithDelta(Carbon::now(), $sut->getUpdatedAt(), 1);
    }

    public function testUpdateEmail()
    {
        $sut = new User('User1', 'test@test.com', '...', 'First', 'Last', new \DateTime());
        $sut->updateEmail('test2@test.com');

        self::assertEquals('test2@test.com', $sut->getEmail());
        self::assertEqualsWithDelta(Carbon::now(), $sut->getUpdatedAt(), 1);
    }

    public function testUpdateProfile()
    {
        $sut = new User('User1', 'test@test.com', '...', 'First', 'Last', new \DateTime());
        $sut->updateProfile('user2', 'test', 'name');

        self::assertEquals('user2', $sut->getName());
        self::assertEquals('test', $sut->getFirstname());
        self::assertEquals('name', $sut->getLastname());
        self::assertEquals('test name', $sut->getFullname());
        self::assertEqualsWithDelta(Carbon::now(), $sut->getUpdatedAt(), 1);
    }
}
