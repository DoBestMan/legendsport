<?php

use Illuminate\Database\Seeder;

class ConfigTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('config')->insert([
            ['commission' => 2,
            'chips' => 10000],
        ]);
    }
}
