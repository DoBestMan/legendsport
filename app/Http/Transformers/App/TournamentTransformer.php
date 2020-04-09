<?php
namespace App\Http\Transformers\App;

use App\Models\Tournament;
use App\Models\TournamentEvent;
use League\Fractal\TransformerAbstract;

class TournamentTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ["games", "players", "prize_pool"];

    public function transform(Tournament $tournament)
    {
        return [
            "buy_in" => $tournament->buy_in,
            "chips" => $tournament->chips,
            "commission" => $tournament->commission,
            "id" => $tournament->id,
            "name" => $tournament->name,
            "players_limit" => $tournament->players_limit,
            "prize_pool_money" => $tournament->getPrizePoolMoney(),
            "starts" => $this->calculateStarts($tournament),
            "state" => $tournament->state,
            "time_frame" => $tournament->time_frame,
        ];
    }

    public function includeGames(Tournament $tournament)
    {
        return $this->collection(
            $tournament->events->map(
                fn(TournamentEvent $tournamentEvent) => $tournamentEvent->apiEvent->api_data,
            ),
            new SportEventTransformer(),
        );
    }

    public function includePlayers(Tournament $tournament)
    {
        $players = $tournament->players->sortByDesc("balance");
        return $this->collection($players, new PlayerTransformer());
    }

    public function includePrizePool(Tournament $tournament)
    {
        return $this->collection($tournament->getPrizes(), new PrizeTransformer());
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
}
