<?php

namespace App\Jobs;

use App\Betting\Bet365\Initaliser;

class RefreshBet365Events
{
    public function handle(Initaliser $client)
    {
        $client->loadAllEvents();
    }
}
