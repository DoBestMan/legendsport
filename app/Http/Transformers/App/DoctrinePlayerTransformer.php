<?php
namespace App\Http\Transformers\App;

use App\Domain\TournamentPlayer;
use League\Fractal\TransformerAbstract;

class DoctrinePlayerTransformer extends TransformerAbstract
{
    public function transform(TournamentPlayer $player)
    {
        return [
            "id" => $player->getId(),
            "name" => $player->getUser()->getName(),
            "chips" => $player->getChips(),
            "balance" => $player->getBalance(),
            "user_id" => $player->getUser()->getId(),
        ];
    }
}
