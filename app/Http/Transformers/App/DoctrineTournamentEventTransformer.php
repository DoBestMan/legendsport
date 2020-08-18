<?php
namespace App\Http\Transformers\App;

use App\Domain\TournamentEvent;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class DoctrineTournamentEventTransformer extends TransformerAbstract
{
    public function transform(TournamentEvent $tournamentEvent)
    {
        $apiEvent = $tournamentEvent->getApiEvent();
        return [
            "external_id" => $apiEvent->getApiId(),
            "id" => $tournamentEvent->getId(),
            "provider" => $apiEvent->getProvider(),
            "score_away" => $apiEvent->getScoreAway(),
            "score_home" => $apiEvent->getScoreHome(),
            "sport_id" => $apiEvent->getSportId(),
            "starts_at" => (new Carbon($apiEvent->getStartsAt()))->toAtomString(),
            "team_away" => $apiEvent->getTeamAway(),
            "team_home" => $apiEvent->getTeamHome(),
            "time_status" => str_replace('_', ' ', ucwords($apiEvent->getTimeStatus()->getValue(), '_')),
        ];
    }
}
