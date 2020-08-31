<?php

namespace App\Betting\Lsports;

use App\Betting\SportEventOdd;
use Decimal\Decimal;

class Parser
{
    public function parseMainLines(iterable $preMatchOdds, $isBaseball)
    {
        $moneyLineHome = $moneyLineAway = $spreadHomePrice = $spreadAwayPrice = $spreadHomeLine = $spreadAwayLine = $overPrice = $underPrice = $totalLine = null;

        $spreads = [];
        $totals = [];

        foreach ($this->flatten($preMatchOdds) as $preMatchOdd) {
            switch (true) {
                case $preMatchOdd['MarketId'] === 226 && $preMatchOdd['Name'] === '1':
                    $moneyLineHome = decimal_to_american($preMatchOdd['Price']);
                    break;
                case $preMatchOdd['MarketId'] === 226 && $preMatchOdd['Name'] === '2':
                    $moneyLineAway = decimal_to_american($preMatchOdd['Price']);
                    break;
                case $preMatchOdd['MarketId'] === 342 && $preMatchOdd['Name'] === '1':
                    if (!$isBaseball || in_array(explode(' ', $preMatchOdd['Line'])[0], [1.5, -1.5])) {
                        $spreads[$preMatchOdd['BaseLine']]['1'] = $preMatchOdd;
                    }
                    break;
                case $preMatchOdd['MarketId'] === 342 && $preMatchOdd['Name'] === '2':
                    if (!$isBaseball || in_array(explode(' ', $preMatchOdd['Line'])[0], [1.5, -1.5])) {
                        $spreads[$preMatchOdd['BaseLine']]['2'] = $preMatchOdd;
                    }
                    break;
                case $preMatchOdd['MarketId'] === 28 && $preMatchOdd['Name'] === 'Over':
                    $totals[$preMatchOdd['BaseLine']]['Over'] = $preMatchOdd;
                    break;
                case $preMatchOdd['MarketId'] === 28 && $preMatchOdd['Name'] === 'Under':
                    $totals[$preMatchOdd['BaseLine']]['Under'] = $preMatchOdd;
                    break;
            }
        }

        $vig = function ($lines) {
            $lines = array_values($lines);
            return abs($lines[0]['Price'] - 2.0) + abs($lines[1]['Price'] - 2.0);
        };

        if (count($spreads)) {
            usort($spreads, fn($a, $b) => $vig($a) <=> $vig($b));
            $spread = current($spreads);

            $spreadHomeLine = new Decimal(explode(' ', $spread['1']['Line'])[0]);
            $spreadHomePrice = decimal_to_american($spread['1']['Price']);
            $spreadAwayLine = new Decimal(explode(' ', $spread['2']['Line'])[0]);
            $spreadAwayPrice = decimal_to_american($spread['2']['Price']);
        }

        if (count($totals)) {
            usort($totals, fn($a, $b) => $vig($a) <=> $vig($b));
            $total = current($totals);

            $underPrice = decimal_to_american($total['Under']['Price']);
            $totalLine = new Decimal($total['Under']['Line']);
            $overPrice = decimal_to_american($total['Over']['Price']);
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
