<?php

use Illuminate\Database\Seeder;
use App\Models\Tournament;

class TournamentsTableSeeder extends Seeder
{
    public function run()
    {
        factory(Tournament::class)
            ->times(5)
            ->create();
    }
}
