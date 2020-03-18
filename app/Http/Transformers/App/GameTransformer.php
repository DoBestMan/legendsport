<?php
namespace App\Http\Transformers\App;

use App\Models\Backstage\TournamentEvent;
use League\Fractal\TransformerAbstract;

class GameTransformer extends TransformerAbstract
{
    public function transform(TournamentEvent $event)
    {
        return [
            "match_time" => $event->apiEvent->api_data["MatchTime"],
            "sport_id" => $event->apiEvent->api_data["Sport"],
            "home_team" => $event->apiEvent->api_data["HomeTeam"],
            "away_team" => $event->apiEvent->api_data["AwayTeam"],
        ];
    }
}
