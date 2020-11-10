<?php
namespace App\Betting;

use App\Betting\SportEvent\Event;
use App\Betting\SportEvent\Sport;
use App\Betting\SportEvent\UpdateCollection;

interface BettingProvider
{
    /** @return Pagination<Event> */
    public function getEvents(int $page): Pagination;

    /** @return Sport[] */
    public function getSports(): array;

    public function getUpdates(): UpdateCollection;
}
