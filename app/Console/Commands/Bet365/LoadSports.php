<?php

namespace App\Console\Commands\Bet365;

use App\Betting\Bet365\Initaliser;
use Illuminate\Console\Command;

class LoadSports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bet365:load:sports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loads bet365 sport id\'s into the database. Should only be called once' ;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Initaliser $client)
    {
        $client->loadSports();
    }
}
