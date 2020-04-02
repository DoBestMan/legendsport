<?php

use App\Models\TournamentPlayer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factory;
use Faker\Generator as Faker;

/** @var Factory $factory */

$factory->define(TournamentPlayer::class, function (Faker $faker) {
    return [
        "tournament_id" => null,
        "user_id" => function () {
            /** @var User $user */
            $user = factory(User::class)->create();
            return $user->id;
        },
        "chips" => $faker->numberBetween(1000, 100000),
    ];
});
