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
            'live_lines' => $tournament->live_lines,
        ];
    }

    public function includeGames(Tournament $tournament)
    {
        return $this->collection($tournament->events, new TournamentEventTransformer());
    }

    public function includePlayers(Tournament $tournament)
    {
        $players = $tournament->players->sortByDesc("chips");
        return $this->collection($players, new PlayerTransformer());
    }

    public function includePrizePool(Tournament $tournament)
    {
        return $this->collection($tournament->getPrizes(), new PrizeTransformer());
    }

    private function calculateStarts(Tournament $tournament): ?string
    {
        return collect($tournament->events)
            ->map(fn(TournamentEvent $event) => format_datetime($event->apiEvent->starts_at))
            ->min();
    }
}
