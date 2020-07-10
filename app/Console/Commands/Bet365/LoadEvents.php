<?php

namespace App\Console\Commands\Bet365;

use App\Betting\Bet365\Initaliser;
use Illuminate\Console\Command;

class LoadEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bet365:load:events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loads bet365 sport id\'s into the database. Should only be called once' ;
    /**
     * @var Initaliser
     */
    private Initaliser $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Initaliser $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->client->loadAllEvents();
    }
}
