<?php
namespace App\Services;

use App\Models\PendingOdd;
use App\Models\Tournament;
use App\Models\TournamentBet;
use App\Models\TournamentBetEvent;
use App\Models\User;
use App\Tournament\BetStatus;
use App\Tournament\NotEnoughBalanceException;
use App\Tournament\NotEnoughChipsException;
use Illuminate\Database\DatabaseManager;

class ParlayBetService
{
    private DatabaseManager $databaseManager;
    private TournamentPlayerService $tournamentPlayerService;

    public function __construct(
        DatabaseManager $databaseManager,
        TournamentPlayerService $tournamentPlayerService
    ) {
        $this->tournamentPlayerService = $tournamentPlayerService;
        $this->databaseManager = $databaseManager;
    }

    /**
     * @param Tournament $tournament
     * @param User $user
     * @param PendingOdd[] $pendingOdds
     * @param int $wager
     * @return TournamentBet
     * @throws NotEnoughChipsException
     * @throws NotEnoughBalanceException
     */
    public function bet(
        Tournament $tournament,
        User $user,
        array $pendingOdds,
        int $wager
    ): TournamentBet {
        return $this->databaseManager->transaction(function () use (
            $tournament,
            $user,
            $pendingOdds,
            $wager
        ) {
            $player = $this->tournamentPlayerService->register($tournament, $user);

            $parlayBet = new TournamentBet();
            $parlayBet->tournament_id = $tournament->id;
            $parlayBet->tournament_player_id = $player->id;
            $parlayBet->chips_wager = $wager;
            $parlayBet->save();

            if ($player->chips < $wager) {
                throw new NotEnoughChipsException();
            }

            $player->chips -= $wager;
            $player->save();

            foreach ($pendingOdds as $pendingOdd) {
                $betEvent = new TournamentBetEvent();
                $betEvent->tournament_bet_id = $parlayBet->id;
                $betEvent->tournament_event_id = $pendingOdd->getTournamentEvent()->id;
                $betEvent->type = $pendingOdd->getType();
                $betEvent->odd = $pendingOdd->getOdd();
                $betEvent->status = BetStatus::PENDING();
                $betEvent->save();
            }

            return $parlayBet;
        });
    }
}
