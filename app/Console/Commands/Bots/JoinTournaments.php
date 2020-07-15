<?php

namespace App\Console\Commands\Bots;

use App\BotPlayers\BotDirector;
use App\Models\Tournament;
use App\Tournament\Events\TournamentUpdate;
use Illuminate\Console\Command;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\Events\Dispatchable;

class JoinTournaments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bots:join-tournaments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets bots to join tournaments' ;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Dispatcher $dispatcher, BotDirector $botDirector)
    {
        $tournamentsAffected = $botDirector->joinTournaments();

        $affectedTournaments = Tournament::whereIn('id', $tournamentsAffected)->get();

        foreach ($affectedTournaments as $tournament) {
            $dispatcher->dispatch(new TournamentUpdate($tournament));
        }
    }
}
