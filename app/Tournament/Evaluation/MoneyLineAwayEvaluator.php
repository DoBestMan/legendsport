<?php
namespace App\Tournament\Evaluation;

use App\Models\TournamentBetEvent;

class MoneyLineAwayEvaluator implements IEvaluator
{
    public function evaluate(TournamentBetEvent $tournamentBetEvent): void
    {
        $apiEvent = $tournamentBetEvent->tournamentEvent->apiEvent;

        if ($apiEvent->score_away > $apiEvent->score_home) {
            $tournamentBetEvent->markAsWin();
        } elseif ($apiEvent->score_away === $apiEvent->score_home) {
            $tournamentBetEvent->markAsPush();
        } else {
            $tournamentBetEvent->markAsLost();
        }
    }
}
