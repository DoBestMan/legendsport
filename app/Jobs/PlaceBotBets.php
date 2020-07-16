<?php

namespace App\Jobs;

use App\BotPlayers\BotDirector;
use App\Models\Tournament;
use App\Tournament\Events\TournamentUpdate;
use Illuminate\Console\Command;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\Events\Dispatchable;

class PlaceBotBets
{
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Dispatcher $dispatcher, BotDirector $botDirector)
    {
        $tournamentsAffected = $botDirector->placeBets();

        $affectedTournaments = Tournament::whereIn('id', $tournamentsAffected)->get();

        foreach ($affectedTournaments as $tournament) {
            $dispatcher->dispatch(new TournamentUpdate($tournament));
        }
    }
}
