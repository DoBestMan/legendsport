<?php

use Illuminate\Database\Seeder;
use App\Models\Backstage\Tournament;

class TournamentsTableSeeder extends Seeder
{
    public function run()
    {
        factory(Tournament::class)->times(5)->create();
    }
}
