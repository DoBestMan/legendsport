<?php

namespace Tests\Fixture\Factory;

use App\Betting\SportEvent\Line;
use App\Betting\SportEvent\Offer;
use App\Betting\TimeStatus;
use App\Domain\ApiEvent;
use Decimal\Decimal;

class ApiEventFactory extends FactoryAbstract
{
    private static array $lines = [
        [[Offer::MONEYLINE, Offer::HOME, Offer::FULL_TIME], 200, null, null],
        [[Offer::MONEYLINE, Offer::AWAY, Offer::FULL_TIME], -200, null, null],
        [[Offer::SPREAD, Offer::HOME, Offer::FULL_TIME], 275, '2', null],
        [[Offer::SPREAD, Offer::AWAY, Offer::FULL_TIME], -125, '-2', null],
        [[Offer::TOTAL, Offer::OVER, Offer::FULL_TIME], 150, '4', null],
        [[Offer::TOTAL, Offer::UNDER, Offer::FULL_TIME], -175, '4', null],
    ];

    public static function create()
    {
        foreach (self::$lines as $lineData) {
            $lineId = implode('::', $lineData[0]);
            $line = $lineData[2] === null ? null : new Decimal($lineData[2]);
            $lines[] = new Line($lineId, $lineData[1], $line, $lineData[3]);
            $offers[] = new Offer($lineId, ...$lineData[0]);
        }

        $apiEvent = new ApiEvent();
        self::setProperty($apiEvent, 'apiId', 'eid');
        self::setProperty($apiEvent, 'timeStatus', TimeStatus::NOT_STARTED());
        self::setProperty($apiEvent, 'id', 1);

        $apiEvent->updateLines(...$lines);
        $apiEvent->updateOffers(...$offers);

        return $apiEvent;
    }

    public static function createStartedEvent()
    {
        foreach (self::$lines as $lineData) {
            $lineId = implode('::', $lineData[0]);
            $line = $lineData[2] === null ? null : new Decimal($lineData[2]);
            $lines[] = new Line($lineId, $lineData[1], $line, $lineData[3]);
            $offers[] = new Offer($lineId, ...$lineData[0]);
        }

        $apiEvent = new ApiEvent();
        self::setProperty($apiEvent, 'apiId', 'started-eid');
        self::setProperty($apiEvent, 'timeStatus', TimeStatus::IN_PLAY());
        self::setProperty($apiEvent, 'id', 2);

        $apiEvent->updateLines(...$lines);
        $apiEvent->updateOffers(...$offers);

        return $apiEvent;
    }
}
