<?php

use Faker\Generator as Faker;
use App\Models\Backstage\Config;

$factory->define(Config::class, function (Faker $faker) {
    return [
        'config' => json_encode([
            'chips' => 10000,
            'commission' => 2, 
            'keep_completed' => 2, 
        ]),
    ];
});