<?php

namespace App\BotPlayers\BettingStrategy;

use App\Domain\Tournament;
use App\Domain\TournamentPlayer;

interface BettingStrategy
{
    public function placeBets(Tournament $tournament, TournamentPlayer $bot, int $hundredChipsToWager, int $remainder = 0): bool;
}
