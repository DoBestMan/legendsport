<?php

namespace App\Domain\BetTypes;

use App\Domain\TournamentBetEvent;
use App\Tournament\Enums\BetStatus;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity() */
class TotalUnder extends TournamentBetEvent
{
    protected function evaluateType(): void
    {
        $eventData = $this->getTournamentEvent()->getApiEvent();
        $handicap = (float) $this->getHandicap();
        if ($handicap === null) {
            //Perhaps throw an exception; handicap must not be null.
            return;
        }

        $result = $eventData->getScoreHome() + $eventData->getScoreAway() - $handicap;

        if ($result < 0) {
            $this->result(BetStatus::WIN());
            return;
        }

        if ($result === 0) {
            $this->result(BetStatus::PUSH());
            return;
        }

        $this->result(BetStatus::LOSS());
    }
}
