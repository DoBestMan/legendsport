<?php
namespace App\Tournament\Evaluation;

use App\Models\TournamentBetEvent;

interface IEvaluator
{
    public function evaluate(TournamentBetEvent $tournamentBetEvent): void;
}
