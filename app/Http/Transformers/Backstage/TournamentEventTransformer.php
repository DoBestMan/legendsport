<?php
namespace App\Http\Transformers\Backstage;

use App\Domain\TournamentEvent;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class TournamentEventTransformer extends TransformerAbstract
{
    public function transform(TournamentEvent $tournamentEvent)
    {
        $apiEvent = $tournamentEvent->getApiEvent();
        return [
            'external_id' => $apiEvent->getApiId(),
            'id' => $apiEvent->getId(),
            'tournament_event_id' => $tournamentEvent->getId(),
            'provider' => $apiEvent->getProvider(),
            'score_away' => $apiEvent->getScoreAway(),
            'score_home' => $apiEvent->getScoreHome(),
            'sport_id' => (string) $apiEvent->getSportId(),
            'starts_at' => (new Carbon($apiEvent->getStartsAt()))->toAtomString(),
            'team_away' => $apiEvent->getTeamAway(),
            'team_home' => $apiEvent->getTeamHome(),
            'status' => str_replace('_', ' ', ucwords($apiEvent->getTimeStatus()->getValue(), '_')),
            'bets_placed' => $tournamentEvent->getBetsPlaced(),
            'bets_graded' => $tournamentEvent->getBetsGraded(),
            'bot_bets_placed' => $tournamentEvent->getBotBetsPlaced(),
            'bot_bets_graded' => $tournamentEvent->getBotBetsGraded()
        ];
    }
}
