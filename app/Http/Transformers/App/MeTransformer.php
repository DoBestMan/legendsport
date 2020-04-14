<?php
namespace App\Http\Transformers\App;

use App\Models\User;
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
        return $this->collection($user->players, new MePlayerTransformer());
    }

    public function includeBets(User $user)
    {
        // TODO Do not return bets from ended tournaments
        return $this->collection($user->bets, new TournamentBetTransformer());
    }
}
