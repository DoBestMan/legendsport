<?php

namespace App\Betting\Lsports;

use App\Betting\SportEventOdd;
use Decimal\Decimal;

class Parser
{
    public function parseMainLines(iterable $preMatchOdds)
    {
        $moneyLineHome = $moneyLineAway = $spreadHomePrice = $spreadAwayPrice = $spreadHomeLine = $spreadAwayLine = $overPrice = $underPrice = $totalLine = null;
        foreach ($this->flatten($preMatchOdds) as $preMatchOdd) {
            switch (true) {
                case $preMatchOdd['MarketId'] === 226 && $preMatchOdd['Name'] === '1':
                    $moneyLineHome = decimal_to_american($preMatchOdd['Price']);
                    break;
                case $preMatchOdd['MarketId'] === 226 && $preMatchOdd['Name'] === '2':
                    $moneyLineAway = decimal_to_american($preMatchOdd['Price']);
                    break;
                case $preMatchOdd['MarketId'] === 342 && $preMatchOdd['Name'] === '1':
                    $spreadHomeLine = new Decimal(explode(' ', $preMatchOdd['Line'])[0]);
                    $spreadHomePrice = decimal_to_american($preMatchOdd['Price']);
                    break;
                case $preMatchOdd['MarketId'] === 342 && $preMatchOdd['Name'] === '2':
                    $spreadAwayLine = new Decimal(explode(' ', $preMatchOdd['Line'])[0]);
                    $spreadAwayPrice = decimal_to_american($preMatchOdd['Price']);
                    break;
                case $preMatchOdd['MarketId'] === 28 && $preMatchOdd['Name'] === 'Over':
                    $totalLine = new Decimal($preMatchOdd['Line']);
                    $overPrice = decimal_to_american($preMatchOdd['Price']);
                    break;
                case $preMatchOdd['MarketId'] === 28 && $preMatchOdd['Name'] === 'Under':
                    $totalLine = new Decimal($preMatchOdd['Line']);
                    $underPrice = decimal_to_american($preMatchOdd['Price']);
                    break;
            }
        }

        return new SportEventOdd(
            '',
            $moneyLineHome,
            $moneyLineAway,
            $spreadHomePrice,
            $spreadAwayPrice,
            $spreadHomeLine,
            $spreadAwayLine,
            $overPrice,
            $underPrice,
            $totalLine
        );
    }

    private function flatten(iterable $iterable)
    {
        foreach ($iterable as $item) {
            yield from $item;
        }
    }
}
