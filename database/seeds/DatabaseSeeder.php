<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(ConfigTableSeeder::class);
        $this->call(TournamentsTableSeeder::class);
        $this->call(AdminTableSeeder::class);
    }
}
