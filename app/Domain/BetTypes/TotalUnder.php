<?php

namespace App\Domain\BetTypes;

use App\Domain\TournamentBetEvent;
use App\Tournament\Enums\BetStatus;
use App\Tournament\Enums\PendingOddType;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity() */
class TotalUnder extends TournamentBetEvent
{
    public const CORRELATION_IDENTIFIER = 'total';
    protected function evaluateType(): bool
    {
        $eventData = $this->getTournamentEvent()->getApiEvent();
        $handicap = (float) $this->getHandicap();
        if ($handicap === null) {
            //Perhaps throw an exception; handicap must not be null.
            return false;
        }

        $result = $eventData->getScoreHome() + $eventData->getScoreAway() - $handicap;

        if ($result < 0) {
            return $this->result(BetStatus::WIN());
        }

        if ($result === 0.0) {
            return $this->result(BetStatus::PUSH());
        }

        return $this->result(BetStatus::LOSS());
    }

    public function getType(): string {
        return PendingOddType::TOTAL_UNDER();
    }
}
