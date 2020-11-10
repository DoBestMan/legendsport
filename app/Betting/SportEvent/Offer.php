<?php

namespace App\Betting\SportEvent;

class Offer
{
    public const HOME = 'home';
    public const AWAY = 'away';
    public const OVER = 'over';
    public const UNDER = 'under';

    public const MONEYLINE = 'moneyline';
    public const SPREAD = 'spread';
    public const TOTAL = 'total';

    public const FULL_TIME = 'fulltime';
    public const FIRST_HALF = 'firsthalf';
    public const SECOND_HALF = 'secondhalf';

    private ?string $id;
    private array $tags;

    public function __construct(?string $id, string ...$tags)
    {
        $this->id = $id;
        $this->tags = $tags;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function tagsToLineName(): string
    {
        switch (true) {
            case in_array(self::TOTAL, $this->tags) && in_array(self::UNDER, $this->tags):
                return 'total_under';
            case in_array(self::TOTAL, $this->tags) && in_array(self::OVER, $this->tags):
                return 'total_over';
            case in_array(self::SPREAD, $this->tags) && in_array(self::HOME, $this->tags):
                return 'spread_home';
            case in_array(self::SPREAD, $this->tags) && in_array(self::AWAY, $this->tags):
                return 'spread_away';
            case in_array(self::MONEYLINE, $this->tags) && in_array(self::HOME, $this->tags):
                return 'moneyline_home';
            case in_array(self::MONEYLINE, $this->tags) && in_array(self::AWAY, $this->tags):
                return 'moneyline_away';
        }

        return '';
    }
}
