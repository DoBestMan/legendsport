<?php

namespace App\Console\Commands\Bots;

use App\BotPlayers\BotDirector;
use App\Jobs\Publishers\PublishTournamentUpdate;
use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\Dispatcher;

class JoinTournaments extends Command
{
    protected $signature = 'bots:join-tournaments';
    protected $description = 'Gets bots to join tournaments' ;

    public function handle(Dispatcher $dispatcher, BotDirector $botDirector): void
    {
        foreach ($botDirector->joinTournaments() as $tournamentId) {
            $dispatcher->dispatch(new PublishTournamentUpdate($tournamentId));
        }
    }
}
