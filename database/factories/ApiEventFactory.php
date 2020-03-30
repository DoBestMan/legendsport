<?php

use App\Models\ApiEvent;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(ApiEvent::class, function (Faker $faker) {
    $id = $faker->uuid;

    return [
        "api_id"   => $id,
        "api_data" => [
            "ID"        => $id,
            "Sport"     => 11,
            "AwayROT"   => "24509",
            "HomeROT"   => "24510",
            "AwayTeam"  => "Ion Cutelaba",
            "HomeTeam"  => "Magomed Ankalaev",
            "MatchTime" => "2020-04-19T01:00:00",
        ],
    ];
});
