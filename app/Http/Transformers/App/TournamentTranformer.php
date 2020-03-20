<?php
namespace App\Http\Transformers\App;

use App\Models\Tournament;
use App\Models\TournamentEvent;
use App\Models\Backstage\TournamentSport;
use League\Fractal\TransformerAbstract;

class TournamentTranformer extends TransformerAbstract
{
    protected $defaultIncludes = ["games", "players"];

    public function transform(Tournament $tournament)
    {
        return [
            "id" => $tournament->id,
            "buy_in" => $tournament->buy_in,
            "enrolled" => 0,
            "name" => $tournament->name,
            "players_limit" => $tournament->players_limit,
            "sport_ids" => $this->calculateSportsIds($tournament),
            "starts" => $this->calculateStarts($tournament),
            "state" => $tournament->state,
            "time_frame" => $tournament->time_frame,
        ];
    }

    public function includeGames(Tournament $tournament)
    {
        return $this->collection($tournament->events, new GameTransformer());
    }

    public function includePlayers(Tournament $tournament)
    {
        return $this->collection($tournament->players, new PlayerTransformer());
    }

    private function calculateStarts(Tournament $tournament)
    {
        return collect($tournament->events)
            ->filter(
                fn(TournamentEvent $event) => array_key_exists(
                    "MatchTime",
                    $event->apiEvent->api_data,
                ),
            )
            ->map(fn(TournamentEvent $event) => $event->apiEvent->api_data["MatchTime"])
            ->min();
    }

    private function calculateSportsIds(Tournament $tournament)
    {
        return $tournament->sports
            ->map(fn(TournamentSport $tournamentSport) => $tournamentSport->sport_id)
            ->all();
    }
}
