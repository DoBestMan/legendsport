<?php
namespace App\Http\Transformers\App;

use App\Domain\TournamentBetEvent;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class DoctrineTournamentBetEventTransformer extends TransformerAbstract
{
    public function transform(TournamentBetEvent $betEvent)
    {
        $apiEvent = $betEvent->getTournamentEvent()->getApiEvent();

        return [
            "id" => $betEvent->getId(),
            "external_id" => $apiEvent->getId(),
            "odd" => $betEvent->getOdd(),
            "score_away" => $apiEvent->getScoreAway(),
            "score_home" => $apiEvent->getScoreHome(),
            "selected_team" => $betEvent->getSelectedTeam(),
            "starts_at" => (new Carbon($apiEvent->getStartsAt()))->toAtomString(),
            "status" => $betEvent->getStatus(),
            "team_away" => $apiEvent->getTeamAway(),
            "team_home" => $apiEvent->getTeamHome(),
            "type" => $betEvent->getType(),
            "handicap" => $betEvent->getHandicap(),
        ];
    }
}
