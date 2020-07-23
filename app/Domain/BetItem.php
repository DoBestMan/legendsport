<?php

namespace App\Domain;

class BetItem
{
    private string $betType;
    private TournamentEvent $event;

    public function __construct(string $betType, TournamentEvent $event)
    {
        $this->betType = $betType;
        $this->event = $event;
    }

    public function getIdentifier(): string
    {
        return $this->event->getId() . '::' . $this->betType;
    }

    public function getCorrelationIdentifier(): string
    {
        return $this->event->getId() . '::' . $this->betType::CORRELATION_IDENTIFIER;
    }

    public function getBetType(): string
    {
        return $this->betType;
    }

    public function getEvent(): TournamentEvent
    {
        return $this->event;
    }

    public function makeBetEvent()
    {
        $betType = $this->betType;
        return new $betType($this->event);
    }
}
