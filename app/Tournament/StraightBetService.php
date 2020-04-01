<?php
namespace App\Tournament;

use App\Models\PendingOdd;
use App\Models\Tournament;
use App\Models\TournamentBet;
use App\Models\TournamentBetEvent;
use App\Models\User;
use App\Services\TournamentPlayerService;
use Illuminate\Database\DatabaseManager;

class StraightBetService
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
     * @return TournamentBet[]
     * @throws NotEnoughChipsException
     * @throws NotEnoughBalanceException
     */
    public function bet(Tournament $tournament, User $user, array $pendingOdds): array
    {
        return $this->databaseManager->transaction(function () use (
            $tournament,
            $user,
            $pendingOdds
        ) {
            $player = $this->tournamentPlayerService->register($tournament, $user);

            $wagersSum = collect($pendingOdds)->sum(
                fn(PendingOdd $pendingOdd) => $pendingOdd->getWager(),
            );
            if ($player->chips < $wagersSum) {
                throw new NotEnoughChipsException();
            }

            $player->chips -= $wagersSum;
            $player->save();

            $tournamentBets = [];
            foreach ($pendingOdds as $pendingOdd) {
                $tournamentBet = new TournamentBet();
                $tournamentBet->tournament_id = $tournament->id;
                $tournamentBet->tournament_player_id = $player->id;
                $tournamentBet->chips_wager = $pendingOdd->getWager();
                $tournamentBet->save();

                $betEvent = new TournamentBetEvent();
                $betEvent->tournament_bet_id = $tournamentBet->id;
                $betEvent->tournament_event_id = $pendingOdd->getTournamentEvent()->id;
                $betEvent->type = $pendingOdd->getType();
                $betEvent->odd = $pendingOdd->getOdd();
                $betEvent->status = BetStatus::PENDING();
                $betEvent->save();

                $tournamentBets[] = $tournamentBet;
            }

            return $tournamentBets;
        });
    }
}
