<?php

use App\Models\TournamentBetEvent;
use App\Tournament\Enums\BetStatus;
use App\Tournament\Enums\PendingOddType;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(TournamentBetEvent::class, function (Faker $faker) {
    return [
        "tournament_bet_id" => null,
        "tournament_event_id" => null,
        "odd" => $faker->numberBetween(-500, 500),
        "handicap" => $faker->numberBetween(-10, 10),
        "status" => BetStatus::PENDING(),
        "type" => PendingOddType::MONEY_LINE_HOME(),
    ];
});
