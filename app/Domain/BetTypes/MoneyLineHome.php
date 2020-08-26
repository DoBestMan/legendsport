<?php

namespace App\Domain\BetTypes;

use App\Domain\TournamentBetEvent;
use App\Tournament\Enums\BetStatus;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity() */
class MoneyLineHome extends TournamentBetEvent
{
    public const CORRELATION_IDENTIFIER = 'result';
    protected function evaluateType(): bool
    {
        $eventData = $this->getTournamentEvent()->getApiEvent();

        $result = $eventData->getScoreHome() - $eventData->getScoreAway();

        if ($result > 0) {
            return $this->result(BetStatus::WIN());
        }

        if ($result === 0) {
            return $this->result(BetStatus::PUSH());
        }

        return $this->result(BetStatus::LOSS());
    }
}
