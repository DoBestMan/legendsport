<?php
namespace App\Http\Transformers\App;

use App\Models\TournamentEvent;
use League\Fractal\TransformerAbstract;

class GameTransformer extends TransformerAbstract
{
    public function transform(TournamentEvent $event)
    {
        return [
            "id"         => $event->id,
            "event_id"   => $event->apiEvent->api_id,
            "match_time" => $event->apiEvent->getMatchTime(),
            "sport_id"   => $event->apiEvent->getSportId(),
            "home_team"  => $event->apiEvent->getHomeTeam(),
            "away_team"  => $event->apiEvent->getAwayTeam(),
        ];
    }
}
