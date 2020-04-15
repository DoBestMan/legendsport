<?php
namespace App\Tournament\Evaluation;

use App\Models\ApiEvent;
use App\Tournament\BetStatus;
use Decimal\Decimal;

interface IEvaluator
{
    public function evaluate(ApiEvent $apiEvent, ?Decimal $handicap): BetStatus;
}
