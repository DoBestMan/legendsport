<?php

use App\Models\ApiEvent;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(ApiEvent::class, function (Faker $faker) {
    $id = $faker->uuid;

    return [
        "api_id" => $id,
        "api_data" => [
            "external_id" => $id,
            "sport_id" => 11,
            "away_team" => "Ion Cutelaba",
            "home_team" => "Magomed Ankalaev",
            "starts_at" => "2020-04-19T01:00:00",
        ],
    ];
});
