<?php

use Faker\Generator as Faker;
use App\Models\Backstage\Tournament;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(Tournament::class, function (Faker $faker) {
    $factoryArray = [
        'avatar' => $faker->numberBetween($min = 0, $max = 1),
        'name' => $faker->name,
        'players_limit' => $faker->randomElement(['Heads-Up', 'Single table', 'Unlimited']),
        'buy_in' => $faker->numberBetween($min = 500, $max = 1000),
        'chips' => $faker->numberBetween($min = 5000, $max = 10000),
        'commission' => $faker->numberBetween($min = 1, $max = 5),
        'prize_pool' => [
            'type' => 'Fixed',
            'fixed_value' => 2,
        ],
        'prizes' => [
            'type' => 'Auto',
        ],
        'state' => $faker->randomElement([
            'Announced',
            'Registering',
            'Late registering',
            'Running',
            'Complete',
            'Cancel',
        ]),
        'time_frame' => $faker->randomElement(['Daily', 'Weekly', 'Monthly', 'Season long']),
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
