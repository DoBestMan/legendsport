<?php

use Illuminate\Database\Seeder;

class ConfigTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('config')->insert([
            ['config' =>  json_encode(
                [
                'chips' => 10000,
                'commission' => 2, 
                'keep_complete' => 2, 
                ]
            ),
            ]
        ]);
    }
}
