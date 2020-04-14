<?php
namespace App\Http\Transformers\App;

use App\Models\TournamentPlayer;
use League\Fractal\TransformerAbstract;

class MePlayerTransformer extends TransformerAbstract
{
    public function transform(TournamentPlayer $player)
    {
        return [
            "id" => $player->id,
            "chips" => $player->chips,
            "pending_chips" => $player->pending_chips,
            "tournament_id" => $player->tournament_id,
        ];
    }
}
