<?php
namespace App\Tournament;

use App\Models\Tournament;
use App\Models\TournamentPlayer;
use Illuminate\Support\Collection;

class TournamentPrizeService
{
    public function creditMoney(Tournament $tournament): void
    {
        /** @var TournamentPlayer[]|Collection $players */
        $players = $tournament->players->sortByDesc("chips");

        $leftIndex = 0;
        foreach ($tournament->getPrizes() as $prize) {
            $rightIndex = $prize->getMaxPosition();
            $playersSlice = $players->slice($leftIndex, $rightIndex - $leftIndex);
            $leftIndex = $rightIndex;

            foreach ($playersSlice as $player) {
                $this->creditMoneyToUser($player, $prize);
            }
        }
    }

    private function creditMoneyToUser(TournamentPlayer $player, Prize $prize): void
    {
        $user = $player->user;
        $user->balance += $prize->getPrize() * 100;
        $user->save();
    }
}
