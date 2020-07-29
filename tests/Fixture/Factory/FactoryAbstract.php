<?php

namespace Tests\Fixture\Factory;

class FactoryAbstract
{
    public static function setProperty($event, $property, $value): void
    {
        (fn() => $event->{$property} = $value)->bindTo($event, get_class($event))();
    }
}
