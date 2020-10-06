<?php
namespace App\Http\Transformers\App;

use App\Models\TournamentPlayer;
use League\Fractal\TransformerAbstract;

class PlayerTransformer extends TransformerAbstract
{
    public function transform(TournamentPlayer $player)
    {
        return [
            "id" => $player->id,
            "name" => $player->user->name,
            "chips" => $player->chips,
            "user_id" => $player->user_id,
        ];
    }
}
