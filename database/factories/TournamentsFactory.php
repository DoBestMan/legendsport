<?php

use Faker\Generator as Faker;
use App\Models\Backstage\Tournaments;

$factory->define(Tournaments::class, function (Faker $faker) {
    $array = array('a'=> 1,'b'=>2);
    return [
            'avatar'=> $faker->numberBetween($min = 0, $max = 1),
            'name'=> $faker->name,
            'type'=> $faker->numberBetween($min = 1, $max = 2),
            'prize_pool'=>
                [
                'type' => 'Auto',
                'fixed_value' => 2, 
                ]
            ,
            'players_limit'=> $faker->numberBetween($min = 1, $max = 10),
            'buy_in'=> $faker->numberBetween($min = 500, $max = 1000),
            'chips'=> $faker->numberBetween($min = 5000, $max = 10000),
            'commission'=> $faker->numberBetween($min = 1, $max = 5),
            'late_register'=> $faker->numberBetween($min = 0, $max = 1),
            'late_register_rule'=> ($array),
            'state'=> $faker->numberBetween($min = 1, $max = 6),
            'prizes'=> $faker->shuffle(array('1', '2', '3')),
    ];
});