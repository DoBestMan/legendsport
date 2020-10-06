<?php

namespace App\Console\Commands\Bots;

use App\BotPlayers\BotDirector;
use App\Jobs\Publishers\PublishTournamentUpdate;
use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\Dispatcher;

class PlaceBets extends Command
{
    protected $signature = 'bots:place-bets';
    protected $description = 'Gets bots to place tournament bets' ;

    public function handle(Dispatcher $dispatcher, BotDirector $botDirector): void
    {
        foreach ($botDirector->placeBets() as $tournamentId) {
            $dispatcher->dispatch(new PublishTournamentUpdate($tournamentId));
        }
    }
}
