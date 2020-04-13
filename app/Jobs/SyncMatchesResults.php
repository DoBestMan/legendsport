<?php
namespace App\Jobs;

use App\Betting\BettingProvider;
use App\Models\ApiEvent;

class SyncMatchesResults
{
    public function handle(BettingProvider $betProvider)
    {
        foreach ($betProvider->getResults() as $result) {
            $apiEvent = ApiEvent::findByApiId($result->getExternalEventId());

            if (!$apiEvent) {
                continue;
            }

            $apiEvent->score_home = $result->getHome();
            $apiEvent->score_away = $result->getAway();
            $apiEvent->time_status = $result->getTimeStatus();
            $apiEvent->save();
        }
    }
}
