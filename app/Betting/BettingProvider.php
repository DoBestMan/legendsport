<?php
namespace App\Betting;

interface BettingProvider
{
    /**
     * @param int $page
     * @return SportEvent[]
     */
    public function getEvents(int $page): array;

    /**
     * @return SportEventOdd[]
     */
    public function getOdds(): array;

    /**
     * @return Sport[]
     */
    public function getSports(): array;
}
