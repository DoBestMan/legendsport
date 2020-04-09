<?php
namespace App\Http\Transformers\App;

use App\Models\TournamentBetEvent;
use League\Fractal\TransformerAbstract;

class TournamentBetEventTransformer extends TransformerAbstract
{
    public function transform(TournamentBetEvent $betEvent)
    {
        $apiData = $betEvent->tournamentEvent->apiEvent->api_data;

        return [
            "away_team" => $apiData->getAwayTeam(),
            "home_team" => $apiData->getHomeTeam(),
            "id" => $betEvent->id,
            "starts_at" => format_datetime($apiData->getStartsAt()),
            "odd" => $betEvent->odd,
            "selected_team" => $betEvent->getSelectedTeam(),
            "status" => $betEvent->status,
            "type" => $betEvent->type,
        ];
    }
}
