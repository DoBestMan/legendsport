<?php
namespace App\Tournament\Evaluation;

use App\Tournament\Enums\PendingOddType;
use Illuminate\Contracts\Foundation\Application;
use UnexpectedValueException;

class EvaluatorFactory
{
    private Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function create(PendingOddType $type): IEvaluator
    {
        switch ($type) {
            case PendingOddType::MONEY_LINE_HOME():
                return $this->app->make(MoneyLineHomeEvaluator::class);
            case PendingOddType::MONEY_LINE_AWAY():
                return $this->app->make(MoneyLineAwayEvaluator::class);
            case PendingOddType::SPREAD_HOME():
                return $this->app->make(SpreadHomeEvaluator::class);
            case PendingOddType::SPREAD_AWAY():
                return $this->app->make(SpreadAwayEvaluator::class);
            case PendingOddType::TOTAL_OVER():
                return $this->app->make(TotalOverEvaluator::class);
            case PendingOddType::TOTAL_UNDER():
                return $this->app->make(TotalUnderEvaluator::class);
            default:
                throw new UnexpectedValueException("Invalid evaluator type [$type]");
        }
    }
}
