<?php

namespace App\Betting\SportsData\OddsFilters;

class MainLines extends \FilterIterator
{
    const BETTING_TYPES = [1, 2, 3];

    public function accept()
    {
        $record = $this->getInnerIterator()->current();

        if (!in_array($record['BettingBetTypeID'], self::BETTING_TYPES)) {
            return false;
        }

        if ($record['BettingMarketTypeID'] !== 1) {
            return false;
        }

        if ($record['BettingPeriodTypeID'] !== 1) {
            return false;
        }

        return true;
    }

}
