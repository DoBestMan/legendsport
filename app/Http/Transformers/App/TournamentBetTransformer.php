<?php
namespace App\Http\Transformers\App;

use App\Models\TournamentBet;
use League\Fractal\TransformerAbstract;

class TournamentBetTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ["events"];

    public function transform(TournamentBet $bet)
    {
        return [
            "id" => $bet->id,
            "chips_wager" => $bet->chips_wager,
            "chips_win" => $bet->chips_win,
            "tournament_id" => $bet->tournament_id,
            "status" => $bet->status,
        ];
    }

    public function includeEvents(TournamentBet $bet)
    {
        return $this->collection($bet->betEvents, new TournamentBetEventTransformer());
    }
}
