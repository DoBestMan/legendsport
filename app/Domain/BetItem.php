<?php

namespace App\Domain;

use App\Betting\SportEvent\Offer;

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
        $correlationType = 'error';
        $tags = explode('_', $this->betType);
        if (in_array(Offer::MONEYLINE, $tags) || in_array(Offer::SPREAD, $tags)) {
            $correlationType = 'result';
        } elseif (in_array(Offer::TOTAL, $tags)) {
            $correlationType = 'total';
        }

        return $this->event->getId() . '::' . $correlationType;
    }

    public function getBetType(): string
    {
        return $this->betType;
    }

    public function getEvent(): TournamentEvent
    {
        return $this->event;
    }

    public function makeBetEvent(TournamentPlayer $tournamentPlayer)
    {
        return new TournamentBetEvent($this->betType, $this->event, $tournamentPlayer);
    }
}
