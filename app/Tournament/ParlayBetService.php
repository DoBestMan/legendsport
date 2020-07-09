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
use App\Tournament\Enums\PendingOddType;
use App\Tournament\Exceptions\BettingProhibitedException;
use App\Tournament\Exceptions\CorrelatedParlayException;
use App\Tournament\Exceptions\DuplicatedOddException;
use App\Tournament\Exceptions\MatchAlreadyStartedException;
use App\Tournament\Exceptions\NotEnoughChipsException;
use App\Tournament\Exceptions\NotRegisteredException;
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
     * @throws NotRegisteredException
     * @throws MatchAlreadyStartedException
     * @throws DuplicatedOddException
     * @throws BettingProhibitedException
     */
    public function bet(
        Tournament $tournament,
        User $user,
        array $pendingOdds,
        int $wager
    ): TournamentBet {
        if (!$tournament->canBetBePlaced()) {
            throw new BettingProhibitedException();
        }

        $duplicates = [];
        $correlated = [];

        foreach ($pendingOdds as $pendingOdd) {
            $timeStatus = $pendingOdd->getTournamentEvent()->apiEvent->time_status;
            if (!$timeStatus->equals(TimeStatus::NOT_STARTED())) {
                throw new MatchAlreadyStartedException();
            }

            switch ((string) $pendingOdd->getType()) {
                case (string) PendingOddType::SPREAD_HOME():
                case (string) PendingOddType::SPREAD_AWAY():
                case (string) PendingOddType::MONEY_LINE_AWAY():
                case (string) PendingOddType::MONEY_LINE_HOME():
                    $correlationType = 'result';
                    break;
                case (string) PendingOddType::TOTAL_OVER():
                case (string) PendingOddType::TOTAL_UNDER():
                    $correlationType = 'total';
                    break;
                default:
                    $correlationType = 'unknown';
            }

            $duplicateKey = "{$pendingOdd->getTournamentEvent()->id}#{$pendingOdd->getType()}";
            $correlateKey = "{$pendingOdd->getTournamentEvent()->id}#{$correlationType}";

            if (isset($duplicates[$duplicateKey])) {
                throw new DuplicatedOddException();
            }

            if (isset($correlated[$correlateKey])) {
                throw new CorrelatedParlayException();
            }

            $duplicates[$duplicateKey] = true;
            $correlated[$correlateKey] = true;
        }

        return $this->databaseManager->transaction(function () use (
            $tournament,
            $user,
            $pendingOdds,
            $wager
        ) {
            $player = $this->tournamentPlayerService->getRegisteredPlayer($tournament, $user);

            if ($player->chips < $wager) {
                throw new NotEnoughChipsException();
            }

            $player->chips -= $wager;
            $player->save();

            $tournamentBet = new TournamentBet();
            $tournamentBet->tournament_id = $tournament->id;
            $tournamentBet->tournament_player_id = $player->id;
            $tournamentBet->chips_wager = $wager;
            $tournamentBet->save();

            foreach ($pendingOdds as $pendingOdd) {
                $betEvent = new TournamentBetEvent();
                $betEvent->tournament_bet_id = $tournamentBet->id;
                $betEvent->tournament_event_id = $pendingOdd->getTournamentEvent()->id;
                $betEvent->type = $pendingOdd->getType();
                $betEvent->odd = $pendingOdd->getOdd();
                $betEvent->handicap = $pendingOdd->getHandicap();
                $betEvent->status = BetStatus::PENDING();
                $betEvent->save();
            }

            return $tournamentBet;
        });
    }
}
