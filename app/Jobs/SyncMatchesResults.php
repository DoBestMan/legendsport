<?php
namespace App\Jobs;

use App\Betting\BettingProvider;
use App\Models\ApiEvent;
use App\Models\Tournament;
use App\Models\TournamentBetEvent;
use App\Models\TournamentEvent;
use App\Models\User;
use App\Tournament\Events\TournamentUpdate;
use App\Tournament\SportEventResultProcessor;
use App\Tournament\TournamentCompletionService;
use App\User\MeUpdate;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;
use Throwable;

final class SyncMatchesResults
{
    public function handle(
        BettingProvider $bettingProvider,
        TournamentCompletionService $tournamentStateService,
        SportEventResultProcessor $sportEventResultProcessor,
        LoggerInterface $logger,
        Dispatcher $dispatcher
    ): void {
        $results = $bettingProvider->getResults();

        // If exception is thrown we don't want to stop code execution.
        // There might be some processed bets that we want to inform users about.
        try {
            $sportEventResultProcessor->processMultiple($results);
        } catch (Throwable $e) {
            $logger->error($e->getMessage(), ["exception" => $e]);
        }

        foreach ($sportEventResultProcessor->getTournamentsUpdated() as $tournament) {
            $tournamentStateService->updateState($tournament);
            $dispatcher->dispatch(new TournamentUpdate($tournament));
        }

        // Inform about updated users bets
        foreach ($sportEventResultProcessor->getUsersUpdated() as $user) {
            $dispatcher->dispatch(new MeUpdate($user));
        }
    }
}
