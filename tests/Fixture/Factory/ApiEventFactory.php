<?php

namespace Tests\Fixture\Factory;

use App\Betting\SportEventOdd;
use Decimal\Decimal;

class ApiEventFactory extends FactoryAbstract
{
    public static function create()
    {
        $apiEvent = new \App\Domain\ApiEvent();
        $odds = new SportEventOdd(
            'eid',
            200,
            -200,
            275,
            -125,
            new Decimal('2'),
            new Decimal('-2'),
            150,
            -175,
            new Decimal('4')
        );
        $apiEvent->updateOdds($odds);
        self::setProperty($apiEvent, 'timeStatus', 'not_started');
        self::setProperty($apiEvent, 'id', 1);

        return $apiEvent;
    }
}
