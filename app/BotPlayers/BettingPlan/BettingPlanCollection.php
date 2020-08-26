<?php

namespace App\BotPlayers\BettingPlan;

use App\BotPlayers\BettingStrategy\ParlayBets;
use App\BotPlayers\BettingStrategy\StraightBets;
use App\BotPlayers\BettingStrategy\WagerCalculator;

class BettingPlanCollection implements \Iterator
{
    private array $plans = [];
    private int $lastKey = -1;

    public function __construct(WagerCalculator $wagerCalculator, array $planDetails)
    {
        $strategies = [];
        foreach ($planDetails as $planDetail) {
            if (!isset($strategies[$planDetail['parlaySize']][$planDetail['minBets']][$planDetail['maxBets']])) {
                if ($planDetail['parlaySize'] === 1) {
                    $strategies[$planDetail['parlaySize']][$planDetail['minBets']][$planDetail['maxBets']] =
                        new StraightBets($wagerCalculator, $planDetail['minBets'], $planDetail['maxBets']);
                } else {
                    $strategies[$planDetail['parlaySize']][$planDetail['minBets']][$planDetail['maxBets']] =
                        new ParlayBets($wagerCalculator, $planDetail['parlaySize'], $planDetail['minBets'], $planDetail['maxBets']);
                }
            }
            $strategy = $strategies[$planDetail['parlaySize']][$planDetail['minBets']][$planDetail['maxBets']];
            $this->plans[] = new BettingPlan($strategy, $planDetail['minChipsPercent'], $planDetail['maxChipsPercent']);
            $this->lastKey++;
        }
    }

    public function current()
    {
        return \current($this->plans);
    }

    public function next()
    {
        return \next($this->plans);
    }

    public function key()
    {
        return \key($this->plans);
    }

    public function valid()
    {
        return \key($this->plans) !== null;
    }

    public function rewind()
    {
        return \reset($this->plans);
    }

    public function isLast(): bool
    {
        return $this->key() === $this->lastKey;
    }
}
