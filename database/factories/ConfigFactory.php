<?php

use Faker\Generator as Faker;
use App\Models\Backstage\Config;

$factory->define(Config::class, function (Faker $faker) {
    return [
        'commission'=> $faker->numberBetween($min = 1, $max = 5),
        'chips'=> $faker->numberBetween($min = 5000, $max = 10000),
    ];
});