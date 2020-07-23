<?php

namespace App\Domain\BetTypes;

use App\Domain\TournamentBetEvent;
use App\Tournament\Enums\BetStatus;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity() */
class MoneyLineAway extends TournamentBetEvent
{
    public const CORRELATION_IDENTIFIER = 'result';
    protected function evaluateType(): void
    {
        $eventData = $this->getTournamentEvent()->getApiEvent();

        $result = $eventData->getScoreAway() - $eventData->getScoreHome();

        if ($result > 0) {
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
