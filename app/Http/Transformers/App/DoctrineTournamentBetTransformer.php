<?php
namespace App\Http\Transformers\App;

use App\Domain\TournamentBet;
use League\Fractal\TransformerAbstract;

class DoctrineTournamentBetTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ["events"];

    public function transform(TournamentBet $bet)
    {
        return [
            "id" => $bet->getId(),
            "chips_wager" => $bet->getChipsWager(),
            "chips_win" => $bet->getChipsWon(),
            "tournament_id" => $bet->getTournament()->getId(),
            "status" => $bet->getStatus(),
        ];
    }

    public function includeEvents(TournamentBet $bet)
    {
        return $this->collection($bet->getEvents(), new DoctrineTournamentBetEventTransformer());
    }
}
