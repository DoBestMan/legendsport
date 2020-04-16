<?php
namespace App\Tournament\Evaluation;

use App\Models\ApiEvent;
use App\Tournament\Enums\BetStatus;
use Decimal\Decimal;

class SpreadAwayEvaluator implements IEvaluator
{
    public function evaluate(ApiEvent $apiEvent, ?Decimal $handicap): BetStatus
    {
        assert($handicap !== null);

        $result = $apiEvent->score_away + $handicap - $apiEvent->score_home;

        if ($result > 0) {
            return BetStatus::WIN();
        }

        if ($result == 0) {
            return BetStatus::PUSH();
        }

        return BetStatus::LOSS();
    }
}
