<?php

namespace App\Betting\SportEvent;

class LineCollection
{
    private array $lines;

    public function __construct(Line ...$lines)
    {
        $this->lines = $lines;
    }

    public function getLines(): array
    {
        return $this->lines;
    }
}
