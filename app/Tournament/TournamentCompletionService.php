<?php
namespace App\Tournament;

use App\Models\ApiEvent;
use App\Models\Tournament;
use App\Models\TournamentEvent;
use App\Tournament\Enums\TournamentState;
use Carbon\Carbon;
use Illuminate\Database\DatabaseManager;

class TournamentCompletionService
{
    private TournamentPrizeService $tournamentPrizeService;
    private DatabaseManager $databaseManager;

    public function __construct(
        TournamentPrizeService $tournamentPrizeService,
        DatabaseManager $databaseManager
    ) {
        $this->tournamentPrizeService = $tournamentPrizeService;
        $this->databaseManager = $databaseManager;
    }

    public function updateState(Tournament $tournament): void
    {
        if ($tournament->isFinished()) {
            return;
        }

        $this->databaseManager->transaction(function () use ($tournament) {
            if ($this->isComplete($tournament)) {
                $tournament->state = TournamentState::COMPLETED();
                $tournament->completed_at = Carbon::now();
                $tournament->save();
                $this->tournamentPrizeService->creditMoney($tournament);
            }
        });
    }

    public function isComplete(Tournament $tournament): bool
    {
        return $tournament->auto_end && $tournament->events
            ->map(fn(TournamentEvent $tournamentEvent) => $tournamentEvent->apiEvent)
            ->every(fn(ApiEvent $apiEvent) => $apiEvent->isFinished());
    }
}
