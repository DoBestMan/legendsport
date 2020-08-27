<?php

namespace App\Domain\BetTypes;

use App\Domain\TournamentBetEvent;
use App\Tournament\Enums\BetStatus;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity() */
class SpreadAway extends TournamentBetEvent
{
    public const CORRELATION_IDENTIFIER = 'result';
    protected function evaluateType(): bool
    {
        $eventData = $this->getTournamentEvent()->getApiEvent();
        $handicap = (float) $this->getHandicap();
        if ($handicap === null) {
            //Perhaps throw an exception; handicap must not be null.
            return false;
        }

        $result = $eventData->getScoreAway() + $handicap - $eventData->getScoreHome();

        if ($result > 0) {
            return $this->result(BetStatus::WIN());
        }

        if ($result === 0.0) {
            return $this->result(BetStatus::PUSH());
        }

        return $this->result(BetStatus::LOSS());
    }
}
