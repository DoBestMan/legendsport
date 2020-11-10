<?php

namespace Unit\Domain;

use App\Domain\Odds;
use PHPUnit\Framework\TestCase;

class OddsTest extends TestCase
{
    /** @dataProvider provideDecimalToAmerican */
    public function testDecimalToAmerican($input, $output)
    {
        self::assertEquals($output, Odds::decimalToAmerican($input));
    }

    public function provideDecimalToAmerican()
    {
        return [
            [false, null],
            [null, null],
            [2, 100],
            [2.5, 150],
            [7, 600],
            [1, null],
            [1.5, -200],
            [1.3333, -300]
        ];
    }

    /** @dataProvider provideAmericanToDecimalWinnings */
    public function testAmericanToDecimalWinnings($input, $output)
    {
        self::assertEquals($output, Odds::americanToDecimalWinnings($input)->toFixed(2));
    }

    public function provideAmericanToDecimalWinnings()
    {
        return [
            [100, 1.00],
            [150, 1.50],
            [600, 6.00],
            [-200, 0.50],
            [-300, 0.33]
        ];
    }
}
