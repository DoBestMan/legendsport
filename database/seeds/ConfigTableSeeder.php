<?php

use Illuminate\Database\Seeder;

class ConfigTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('config')->insert([
            ['config' =>  json_encode(
                [
                'chips' => 1,
                'commission' => 50000, 
                'keep_complete' => 2, 
                ]
            ),
            ]
        ]);
    }
}
