<?php
namespace App\Tournament\Evaluation;

use App\Models\ApiEvent;
use App\Tournament\BetStatus;
use Decimal\Decimal;

class SpreadHomeEvaluator implements IEvaluator
{
    public function evaluate(ApiEvent $apiEvent, ?Decimal $handicap): BetStatus
    {
        $result = $apiEvent->score_home + $handicap - $apiEvent->score_away;

        if ($result > 0) {
            return BetStatus::WIN();
        }

        if ($result == 0) {
            return BetStatus::PUSH();
        }

        return BetStatus::LOSS();
    }
}
