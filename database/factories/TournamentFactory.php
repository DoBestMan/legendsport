<?php

use App\Tournament\Enums\PlayersLimit;
use App\Tournament\Enums\TimeFrame;
use App\Tournament\Enums\TournamentState;
use Faker\Generator as Faker;
use App\Models\Tournament;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(Tournament::class, function (Faker $faker) {
    $factoryArray = [
        'avatar' => $faker->numberBetween($min = 0, $max = 1),
        'name' => $faker->name,
        'players_limit' => $faker->randomElement(PlayersLimit::values()),
        'buy_in' => $faker->numberBetween($min = 5, $max = 10) * 100,
        'chips' => $faker->numberBetween($min = 50, $max = 100) * 100,
        'commission' => $faker->numberBetween($min = 1, $max = 5) * 100,
        'prize_pool' => [
            'type' => 'Fixed',
            'fixed_value' => 2,
        ],
        'state' => TournamentState::REGISTERING(),
        'time_frame' => $faker->randomElement(TimeFrame::values()),
    ];

    if ($factoryArray['players_limit'] == 'Unlimited') {
        $factoryArray = array_merge($factoryArray, [
            'late_register' => $faker->numberBetween($min = 0, $max = 1),
            'late_register_rule' => [
                'interval' => $faker->randomElement(['seconds', 'minutes', 'hours', 'days']),
                'value' => $faker->numberBetween($min = 1, $max = 60),
            ],
        ]);
    } else {
        $factoryArray = array_merge($factoryArray, [
            'late_register' => null,
            'late_register_rule' => [
                'interval' => null,
                'value' => null,
            ],
        ]);
    }

    return $factoryArray;
});
