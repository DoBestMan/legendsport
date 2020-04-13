<?php
namespace App\Http\Transformers\App;

use App\Models\TournamentBetEvent;
use League\Fractal\TransformerAbstract;

class TournamentBetEventTransformer extends TransformerAbstract
{
    public function transform(TournamentBetEvent $betEvent)
    {
        $apiEvent = $betEvent->tournamentEvent->apiEvent;

        return [
            "id" => $betEvent->id,
            "external_id" => $apiEvent->api_id,
            "odd" => $betEvent->odd,
            "score_away" => $apiEvent->score_away,
            "score_home" => $apiEvent->score_home,
            "selected_team" => $betEvent->getSelectedTeam(),
            "starts_at" => format_datetime($apiEvent->starts_at),
            "status" => $betEvent->status,
            "team_away" => $apiEvent->team_away,
            "team_home" => $apiEvent->team_home,
            "type" => $betEvent->type,
        ];
    }
}
