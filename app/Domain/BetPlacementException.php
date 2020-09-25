<?php

namespace App\Domain;

class BetPlacementException extends \DomainException
{
    public static function tournamentOver()
    {
        return new self('You cannot place a bet in this tournament');
    }

    public static function eventStarted()
    {
        return new self('The event has already begun.');
    }

    public static function notEnoughChips()
    {
        return new self('You don\'t have enough chips.');
    }

    public static function notRegistered()
    {
        return new self('You need to be registered to place a bet.');
    }

    public static function invalidEvent()
    {
        return new self('That event is not part of this tournament');
    }

    public static function correlatedEvents()
    {
        return new self('These events are correlated and cannot be parlayed together, please select different events');
    }

    public static function insufficientEvents()
    {
        return new self('Must be at least 2 bet items to place a parlay');
    }

    public static function lineSuspended()
    {
        return new self('One or more betting lines have been suspended');
    }
}
