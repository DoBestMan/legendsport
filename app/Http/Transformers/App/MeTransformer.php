<?php
namespace App\Http\Transformers\App;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class MeTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        "players",
    ];

    public function transform(User $user)
    {
        return [
            "id" => $user->id,
            "name" => $user->name,
            "balance" => $user->balance,
        ];
    }

    public function includePlayers(User $user)
    {
        return $this->collection($user->players, new MePlayerTransformer());
    }
}
