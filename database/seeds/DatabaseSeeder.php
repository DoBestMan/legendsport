<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(ConfigTableSeeder::class);
    }

    public function truncateTables(Array $tables)
    {
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }
    }

}
