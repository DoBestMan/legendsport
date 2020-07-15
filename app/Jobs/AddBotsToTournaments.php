<?php

namespace App\Jobs;

use App\BotPlayers\BotDirector;
use App\Models\Tournament;
use App\Tournament\Events\TournamentUpdate;
use Illuminate\Contracts\Events\Dispatcher;

class AddBotsToTournaments
{
    public function handle(Dispatcher $dispatcher, BotDirector $botDirector)
    {
        $tournamentsAffected = $botDirector->joinTournaments();

        $affectedTournaments = Tournament::whereIn('id', $tournamentsAffected)->get();

        foreach ($affectedTournaments as $tournament) {
            $dispatcher->dispatch(new TournamentUpdate($tournament));
        }
    }
}
