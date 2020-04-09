<?php
namespace App\Http\Transformers\App;

use App\Models\TournamentBetEvent;
use League\Fractal\TransformerAbstract;

class TournamentBetEventTransformer extends TransformerAbstract
{
    public function transform(TournamentBetEvent $betEvent)
    {
        return [
            "away_team" => $betEvent->tournamentEvent->apiEvent->api_data->getAwayTeam(),
            "home_team" => $betEvent->tournamentEvent->apiEvent->api_data->getHomeTeam(),
            "id" => $betEvent->id,
            "match_time" => $betEvent->tournamentEvent->apiEvent->api_data
                ->getStartsAt()
                ->toAtomString(),
            "odd" => $betEvent->odd,
            "selected_team" => $betEvent->getSelectedTeam(),
            "status" => $betEvent->status,
            "type" => $betEvent->type,
        ];
    }
}
