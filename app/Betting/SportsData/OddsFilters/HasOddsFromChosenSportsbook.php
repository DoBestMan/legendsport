<?php

namespace App\Betting\SportsData\OddsFilters;

class HasOddsFromChosenSportsbook extends \FilterIterator
{
    const SPORTSBOOK_ID = 7;

    public function accept()
    {
        $record = $this->getInnerIterator()->current();

        if ($record['AnyBetsAvailable'] === false) {
            return false;
        }

        foreach ($record['AvailableSportsbooks'] as $sportsBook) {
            if ($sportsBook['SportsbookID'] === self::SPORTSBOOK_ID) {
                return true;
            }
        }

        return false;
    }

    public function current()
    {
        $record = $this->getInnerIterator()->current();

        $filteredOutcomes = [];

        foreach ($record['BettingOutcomes'] as $bettingOutcome) {
            if ($bettingOutcome['SportsBook']['SportsbookID'] === self::SPORTSBOOK_ID) {
                $filteredOutcomes[] = $bettingOutcome;
            }
        }

        $record['BettingOutcomes'] = $filteredOutcomes;

        return $record;
    }
}
