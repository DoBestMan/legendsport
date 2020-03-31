<?php
namespace App\Http\Transformers\App;

use App\Models\TournamentPlayer;
use League\Fractal\TransformerAbstract;

// TODO Simplify it
class PlayerTransformer extends TransformerAbstract
{
    public function transform(TournamentPlayer $player)
    {
        return [
            "id" => $player->id,
            "bets" => $player->bets,
            "chips" => $player->chips,
        ];
    }
}
