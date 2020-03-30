<?php

use App\Models\ApiEvent;
use App\Models\TournamentEvent;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(TournamentEvent::class, function () {
    return [
        "tournament_id" => null,
        "api_event_id" => function () {
            /** @var ApiEvent $apiEvent */
            $apiEvent = factory(ApiEvent::class)->create();
            return $apiEvent->id;
        },
    ];
});
