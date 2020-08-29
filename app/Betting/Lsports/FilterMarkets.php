<?php

namespace App\Betting\Lsports;

class FilterMarkets extends \FilterIterator
{
    const MARKETS = [
        226,
        342,
        28
    ];

    public function accept()
    {
        $record = $this->getInnerIterator()->current();

        if (in_array($record['Id'], self::MARKETS)) {
            return true;
        }

        return false;
    }

    public function current()
    {
        $record = $this->getInnerIterator()->current();
        return new AddMarketId(new HasOddsFromChosenSportsbook(new \ArrayIterator($record['Providers'])), $record['Id']);
    }
}
