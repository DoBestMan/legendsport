<?php

namespace Tests\Fixture\Factory;

use App\Betting\SportEventOdd;
use App\Betting\TimeStatus;
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
        self::setProperty($apiEvent, 'apiId', 'eid');
        self::setProperty($apiEvent, 'timeStatus', TimeStatus::NOT_STARTED());
        self::setProperty($apiEvent, 'id', 1);

        return $apiEvent;
    }

    public static function createStartedEvent()
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
        self::setProperty($apiEvent, 'apiId', 'started-eid');
        self::setProperty($apiEvent, 'timeStatus', TimeStatus::IN_PLAY());
        self::setProperty($apiEvent, 'id', 2);

        return $apiEvent;
    }
}
