<?php
namespace App\Betting;

interface BettingProvider
{
    /**
     * @param int $page
     * @return Pagination<SportEvent>
     */
    public function getEvents(int $page): Pagination;

    /**
     * @return SportEventOdd[]
     */
    public function getOdds(): array;

    /**
     * @return SportEventResult[]
     */
    public function getResults(): array;

    /**
     * @return Sport[]
     */
    public function getSports(): array;
}
