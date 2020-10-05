<?php
namespace App\Http\Transformers\App;

use App\Domain\Tournament;
use App\Domain\TournamentEvent;
use Carbon\Carbon;
use Doctrine\Common\Collections\Criteria;
use League\Fractal\TransformerAbstract;

class DoctrineTournamentTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'games',
        'players',
        'prize_pool'
    ];

    public function transform(Tournament $tournament)
    {
        return [
            "buy_in" => $tournament->getBuyIn(),
            "chips" => $tournament->getChips(),
            "commission" => $tournament->getCommission(),
            "id" => $tournament->getId(),
            "name" => $tournament->getName(),
            "players_limit" => $tournament->getPlayersLimit(),
            "prize_pool_money" => $tournament->getPrizePoolMoney(),
            "starts" => (new Carbon($tournament->getRegistrationDeadline()))->toAtomString(),
            "state" => $tournament->getState(),
            "time_frame" => $tournament->getTimeFrame(),
            'live_lines' => $tournament->hasLiveLines(),
        ];
    }

    public function includeGames(Tournament $tournament)
    {
        return $this->collection($tournament->getEvents(), new DoctrineTournamentEventTransformer());
    }

    public function includePlayers(Tournament $tournament)
    {
        $players = $tournament->getPlayers()->matching(Criteria::create()->orderBy(['chips' => Criteria::DESC]));
        return $this->collection($players, new DoctrinePlayerTransformer());
    }

    public function includePrizePool(Tournament $tournament)
    {
        return $this->collection($tournament->getPrizes(), new PrizeTransformer());
    }
}
