<?php
namespace App\Tournament;

use App\Domain\Tournament as TournamentEntity;
use App\Models\ApiEvent;
use App\Models\Tournament;
use App\Models\TournamentBetEvent;
use App\Models\TournamentEvent;
use App\Models\TournamentPlayer;
use App\Tournament\Enums\BetStatus;
use App\Tournament\Enums\TournamentState;
use Carbon\Carbon;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Collection;

class TournamentCompletionService
{
    private DatabaseManager $databaseManager;

    public function __construct(
        DatabaseManager $databaseManager
    ) {
        $this->databaseManager = $databaseManager;
    }

    public function updateState($tournament): bool
    {
        if ($tournament instanceof TournamentEntity) {
            $tournament = Tournament::find($tournament->getId());
        }

        if ($tournament->isFinished()) {
            return false;
        }

        $this->databaseManager->transaction(function () use ($tournament) {
            if ($this->isComplete($tournament)) {
                $tournament->state = TournamentState::COMPLETED();
                $tournament->completed_at = Carbon::now();
                $tournament->save();
                $this->creditMoney($tournament);
            }
        });

        return true;
    }

    public function isComplete(Tournament $tournament): bool
    {
        return $tournament->auto_end && $tournament->events
            ->map(fn(TournamentEvent $tournamentEvent) => $tournamentEvent->apiEvent)
            ->every(fn(ApiEvent $apiEvent) => $apiEvent->isFinished()) &&
            $tournament->events->map(fn (TournamentEvent $tournamentEvent) => $tournamentEvent->tournamentBetEvents
                ->map(fn (TournamentBetEvent $tournamentBetEvent) => !$tournamentBetEvent->tournamentBet->status->equals(BetStatus::PENDING()))
                ->every(fn (bool $betHasGraded) => $betHasGraded === true)
            )->every(fn (bool $eventIsFullyGraded) => $eventIsFullyGraded === true);
    }

    public function creditMoney(Tournament $tournament): void
    {
        /** @var TournamentPlayer[]|Collection $players */
        $players = $tournament->players->sortByDesc("chips");

        $leftIndex = 0;
        foreach ($tournament->getPrizes() as $prize) {
            $rightIndex = $prize->getMaxPosition();
            $playersSlice = $players->slice($leftIndex, $rightIndex - $leftIndex);
            $leftIndex = $rightIndex;

            foreach ($playersSlice as $player) {
                $this->creditMoneyToUser($player, $prize);
            }
        }
    }

    private function creditMoneyToUser(TournamentPlayer $player, PrizeMoney $prize): void
    {
        $user = $player->user;
        $user->balance += $prize->getPrizeMoney();
        $user->save();
    }
}
