<?php

namespace App\Domain\ApiEvent;

class UpdateResult
{
    private bool $linesUpdated;
    private bool $offersUpdated;
    private bool $fixtureUpdated;
    private bool $hasLinesToGrade;

    public function __construct(bool $linesUpdated, bool $offersUpdated, bool $fixtureUpdated, bool $hasLinesToGrade)
    {
        $this->linesUpdated = $linesUpdated;
        $this->offersUpdated = $offersUpdated;
        $this->fixtureUpdated = $fixtureUpdated;
        $this->hasLinesToGrade = $hasLinesToGrade;
    }

    public function haveLinesUpdated(): bool
    {
        return $this->linesUpdated;
    }

    public function haveOffersUpdated(): bool
    {
        return $this->offersUpdated;
    }

    public function hasFixtureUpdated(): bool
    {
        return $this->fixtureUpdated;
    }

    public function hasLinesToGrade(): bool
    {
        return $this->hasLinesToGrade;
    }

    public function hasUpdated(): bool
    {
        return $this->fixtureUpdated || $this->offersUpdated || $this->linesUpdated || $this->hasLinesToGrade;
    }
}
