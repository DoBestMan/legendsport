<?php
namespace App\Tournament;

use App\Models\ApiEvent;
use App\Models\Tournament;
use App\Models\TournamentEvent;
use App\Tournament\Enums\TournamentState;
use Illuminate\Database\DatabaseManager;

class TournamentStateService
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
            $tournament->state = $this->calculateCurrentState($tournament);
            $tournament->save();

            if ($tournament->state->equals(TournamentState::COMPLETED())) {
                $this->tournamentPrizeService->creditMoney($tournament);
            }
        });
    }

    public function calculateCurrentState(Tournament $tournament): TournamentState
    {
        $allEventsAreFinished = $tournament->events
            ->flatMap(fn(TournamentEvent $tournamentEvent) => $tournamentEvent->apiEvent)
            ->every(fn(ApiEvent $apiEvent) => $apiEvent->isFinished());

        if ($allEventsAreFinished) {
            return TournamentState::COMPLETED();
        }

        // TODO Add more state transitions

        return $tournament->state;
    }
}
