<?php
namespace App\Tournament;

use App\Betting\TimeStatus;
use App\Models\PendingOdd;
use App\Models\Tournament;
use App\Models\TournamentBet;
use App\Models\TournamentBetEvent;
use App\Models\User;
use App\Services\TournamentPlayerService;
use App\Tournament\Enums\BetStatus;
use App\Tournament\Exceptions\BettingProhibitedException;
use App\Tournament\Exceptions\MatchAlreadyStartedException;
use App\Tournament\Exceptions\NotEnoughChipsException;
use App\Tournament\Exceptions\NotRegisteredException;
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
     * @throws NotRegisteredException
     * @throws MatchAlreadyStartedException
     */
    public function bet(Tournament $tournament, User $user, array $pendingOdds): array
    {
        if (!$tournament->canBetBePlaced()) {
            throw new BettingProhibitedException();
        }

        foreach ($pendingOdds as $pendingOdd) {
            $timeStatus = $pendingOdd->getTournamentEvent()->apiEvent->time_status;
            if (!$timeStatus->equals(TimeStatus::NOT_STARTED())) {
                throw new MatchAlreadyStartedException();
            }
        }

        return $this->databaseManager->transaction(function () use (
            $tournament,
            $user,
            $pendingOdds
        ) {
            $player = $this->tournamentPlayerService->getRegisteredPlayer($tournament, $user);

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
                $betEvent->handicap = $pendingOdd->getHandicap();
                $betEvent->status = BetStatus::PENDING();
                $betEvent->save();

                $tournamentBets[] = $tournamentBet;
            }

            return $tournamentBets;
        });
    }
}
