<?php

namespace App\BotPlayers\BettingPlan;

use App\BotPlayers\BettingStrategy\BettingStrategy;

class BettingPlan
{
    private int $minChipsPercent;
    private int $maxChipsPercent;
    private BettingStrategy $strategy;

    public function __construct(BettingStrategy $strategy, int $minChipsPercent, int $maxChipsPercent)
    {
        $this->minChipsPercent = $minChipsPercent;
        $this->maxChipsPercent = $maxChipsPercent;
        $this->strategy = $strategy;
    }

    public function getMinChipsPercent(): int
    {
        return $this->minChipsPercent;
    }

    public function getMaxChipsPercent(): int
    {
        return $this->maxChipsPercent;
    }

    public function getStrategy(): BettingStrategy
    {
        return $this->strategy;
    }

    public function getChipsPercent()
    {
        return rand($this->minChipsPercent, $this->maxChipsPercent) * 0.01;
    }
}
