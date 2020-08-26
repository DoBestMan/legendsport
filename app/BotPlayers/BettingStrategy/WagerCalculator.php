<?php

namespace App\BotPlayers\BettingStrategy;

class WagerCalculator
{
    public function calculateWagers(int $straightBetChips, int $betsToPlace): array
    {
        $wagers = [0, $straightBetChips];
        for ($i = 0; $i < $betsToPlace - 1; $i++) {
            $wagers[] = intval(rand(0, $straightBetChips));
        }

        sort($wagers);
        $wagersToPlace = [];
        foreach ($wagers as $i => $wager) {
            if ($i === 0) {
                continue;
            }
            $wagersToPlace[] = $wager - $wagers[$i - 1];
        }

        return array_values(array_filter($wagersToPlace));
    }
}
