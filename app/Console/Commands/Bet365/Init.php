<?php

namespace App\Console\Commands\Bet365;

use App\Betting\Bet365\Initaliser;
use Illuminate\Console\Command;

class Init extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bet365:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Init bet365 local cache' ;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Initaliser $client)
    {
        $client->loadSports();
        $client->loadAllLeagues();
        $client->loadAllEvents();
    }
}
