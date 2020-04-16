<?php
namespace App\Tournament;

use App\Tournament\Enums\BetStatus;
use Illuminate\Support\Collection;

class BetStatusCalculator
{
    private Collection $betStatuses;

    public function __construct(iterable $betStatuses)
    {
        $this->betStatuses = collect($betStatuses);
    }

    public function calculate(): BetStatus
    {
        if ($this->betStatuses->some(fn(BetStatus $s) => $s->equals(BetStatus::PENDING()))) {
            return BetStatus::PENDING();
        }

        if ($this->betStatuses->some(fn(BetStatus $s) => $s->equals(BetStatus::LOSS()))) {
            return BetStatus::LOSS();
        }

        if ($this->betStatuses->every(fn(BetStatus $s) => $s->equals(BetStatus::PUSH()))) {
            return BetStatus::PUSH();
        }

        return BetStatus::WIN();
    }
}
