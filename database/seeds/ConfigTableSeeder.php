<?php

use Illuminate\Database\Seeder;
use App\Models\Config;

class ConfigTableSeeder extends Seeder
{
    public function run()
    {
        factory(Config::class)
            ->times(1)
            ->create();
    }
}
