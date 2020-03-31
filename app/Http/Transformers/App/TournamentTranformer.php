<?php
namespace App\Http\Transformers\App;

use App\Models\Tournament;
use App\Models\TournamentEvent;
use App\Models\TournamentPlayer;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class TournamentTranformer extends TransformerAbstract
{
    protected $defaultIncludes = ["games", "players"];

    private ?User $user;

    public function __construct(?User $user = null)
    {
        $this->user = $user;
    }

    public function transform(Tournament $tournament)
    {
        return [
            "balance" => $this->calculateBalance($tournament),
            "buy_in" => $tournament->buy_in,
            "enrolled" => 0, // TODO Change it
            "id" => $tournament->id,
            "name" => $tournament->name,
            "players_limit" => $tournament->players_limit,
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

    private function calculateBalance(Tournament $tournament)
    {
        if ($this->user) {
            /** @var TournamentPlayer $player */
            $player = $this->user->players->first(
                fn(TournamentPlayer $player) => $player->tournament_id === $tournament->id
            );

            if ($player) {
                return $player->chips;
            }
        }

        return 0;
    }

    private function calculateStarts(Tournament $tournament)
    {
        return collect($tournament->events)
            ->filter(
                fn(TournamentEvent $event) => array_key_exists(
                    "MatchTime",
                    $event->apiEvent->api_data
                )
            )
            ->map(fn(TournamentEvent $event) => $event->apiEvent->api_data["MatchTime"])
            ->min();
    }
}
