<?php

namespace App\Domain;

class BetItem
{
    private const CLASS_MAP = [
        'money_line_home' => BetTypes\MoneyLineHome::class,
        'money_line_away' => BetTypes\MoneyLineAway::class,
        'spread_home' => BetTypes\SpreadHome::class,
        'spread_away' => BetTypes\SpreadAway::class,
        'total_under' => BetTypes\TotalUnder::class,
        'total_over' => BetTypes\TotalOver::class,
    ];

    private string $betType;
    private TournamentEvent $event;

    public function __construct(string $betType, TournamentEvent $event)
    {
        $this->betType = $betType;
        $this->event = $event;
    }

    public static function createFromBetTypeAlias(string $betTypeAlias, TournamentEvent $event): self
    {
        return new self(self::CLASS_MAP[$betTypeAlias], $event);
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
