<?php

namespace App\Betting\Lsports;

class HasOddsFromChosenSportsbook extends \FilterIterator
{
    const PINNACLE = 4;
    const FIVEDIMES = 32;
    private int $sportsbookId = self::PINNACLE;

    public function accept()
    {
        $record = $this->getInnerIterator()->current();

        if ($record['Id'] === $this->sportsbookId) {
            return true;
        }

        return false;
    }

    public function current()
    {
        return $this->getInnerIterator()->current()['Bets'];
    }
}
