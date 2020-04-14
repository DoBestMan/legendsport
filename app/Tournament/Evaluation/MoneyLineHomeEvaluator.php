<?php
namespace App\Tournament\Evaluation;

use App\Models\TournamentBetEvent;

class MoneyLineHomeEvaluator implements IEvaluator
{
    public function evaluate(TournamentBetEvent $tournamentBetEvent): void
    {
        $apiEvent = $tournamentBetEvent->tournamentEvent->apiEvent;

        if ($apiEvent->score_home > $apiEvent->score_away) {
            $tournamentBetEvent->markAsWin();
        } elseif ($apiEvent->score_away === $apiEvent->score_home) {
            $tournamentBetEvent->markAsPush();
        } else {
            $tournamentBetEvent->markAsLost();
        }
    }
}
