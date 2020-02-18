<?php

use Illuminate\Database\Seeder;
use App\Models\Backstage\Tournaments;

class TournamentsTableSeeder extends Seeder
{
    public function run()
    {
        factory(Tournaments::class)->times(5)->create();
    }
}
