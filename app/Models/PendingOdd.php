<?php
namespace App\Models;

use App\Tournament\PendingOddType;

class PendingOdd
{
    private TournamentEvent $tournamentEvent;
    private PendingOddType $type;
    private ?int $wager;
    private ?int $odd;

    public function __construct(
        PendingOddType $type,
        TournamentEvent $tournamentEvent,
        ?int $wager = null,
        ?int $odd = null
    ) {
        $this->type = $type;
        $this->tournamentEvent = $tournamentEvent;
        $this->wager = $wager;
        $this->odd = $odd;
    }

    public function getTournamentEvent(): TournamentEvent
    {
        return $this->tournamentEvent;
    }

    public function getType(): PendingOddType
    {
        return $this->type;
    }

    public function getWager(): ?int
    {
        return $this->wager;
    }

    public function getOdd(): ?int
    {
        return $this->odd;
    }

    public function setOdd(int $odd): void
    {
        $this->odd = $odd;
    }
}
