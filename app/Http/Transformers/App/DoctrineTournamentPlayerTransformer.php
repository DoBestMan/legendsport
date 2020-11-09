<?php
namespace App\Http\Transformers\App;

use App\Domain\TournamentBet;
use App\Domain\TournamentPlayer;
use League\Fractal\TransformerAbstract;

class DoctrineTournamentPlayerTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ["bets"];

    public function transform(TournamentPlayer $player)
    {
        return [
            "id" => $player->getId(),
            "name" => $player -> getUser() -> getName(),
            "tournamentId" => $player -> getTournament() -> getId(),
        ];
    }

    public function includeBets(TournamentPlayer $player)
    {
        $bets  = $player->getSortedBetsByWin();
        return $this->collection($bets, new DoctrineTournamentBetTransformer());
    }
}
