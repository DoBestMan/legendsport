<?php

namespace App\Betting;

use App\Domain\ApiEvent;

interface SingleEventUpdater
{
    public function updateEventOdds(ApiEvent $apiEvent);
}
