<?php

use App\Models\Config;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(Config::class, function () {
    return [
        'config' => json_encode([
            'chips' => 10000,
            'commission' => 2,
            'keep_completed' => 2,
        ]),
    ];
});
