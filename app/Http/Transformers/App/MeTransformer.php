<?php
namespace App\Http\Transformers\App;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use League\Fractal\TransformerAbstract;

class MeTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ["bets", "players"];

    public function transform(User $user)
    {
        return [
            "id" => $user->id,
            "name" => $user->name,
            "balance" => $user->balance,
            "token" => $user->getToken(),
        ];
    }

    public function includePlayers(User $user)
    {
        $players = $user
            ->players()
            ->with(["bets"])
            ->whereHas("tournament", fn(Builder $query) => $query->active())
            ->get();

        return $this->collection($players, new MePlayerTransformer());
    }

    public function includeBets(User $user)
    {
        $bets = $user
            ->bets()
            ->with(["betEvents.tournamentEvent.apiEvent"])
            ->whereHas("tournament", fn(Builder $query) => $query->active())
            ->get();

        return $this->collection($bets, new TournamentBetTransformer());
    }
}
