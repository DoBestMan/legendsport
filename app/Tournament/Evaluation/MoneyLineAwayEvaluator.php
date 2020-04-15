<?php
namespace App\Tournament\Evaluation;

use App\Models\ApiEvent;
use App\Tournament\BetStatus;
use Decimal\Decimal;

class MoneyLineAwayEvaluator implements IEvaluator
{
    public function evaluate(ApiEvent $apiEvent, ?Decimal $handicap): BetStatus
    {
        $result = $apiEvent->score_away - $apiEvent->score_home;

        if ($result > 0) {
            return BetStatus::WIN();
        }

        if ($result === 0) {
            return BetStatus::PUSH();
        }

        return BetStatus::LOSS();
    }
}
