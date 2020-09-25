<?php

use App\Betting\TestData;
use App\Betting\TimeStatus;
use App\Models\ApiEvent;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(ApiEvent::class, function (Faker $faker) {
    $id = $faker->uuid;

    return [
        "api_id" => $id,
        "provider" => TestData::PROVIDER_NAME,
        "sport_id" => 11,
        "team_away" => "Ion Cutelaba",
        "team_home" => "Magomed Ankalaev",
        "time_status" => TimeStatus::NOT_STARTED(),
        "starts_at" => "2020-04-19T01:00:00",
    ];
});
