<?php
namespace App\Betting;

interface BettingProvider
{
    /**
     * @return SportEvent[]
     */
    public function getEvents(): array;

    /**
     * @return SportEventOdd[]
     */
    public function getOdds(): array;

    /**
     * @return Sport[]
     */
    public function getSports(): array;
}
