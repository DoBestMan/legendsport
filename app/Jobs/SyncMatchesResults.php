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

        $affectedTournaments = $this->getAffectedTournaments(
            $sportEventResultProcessor->getProcessedApiEvents(),
        );
        $affectedUsers = $this->getAffectedUsers(
            $sportEventResultProcessor->getProcessedBetEvents(),
        );

        // Update tournaments state
        foreach ($affectedTournaments as $tournament) {
            $tournamentStateService->updateState($tournament);
        }

        // Inform about updated tournaments results
        foreach ($affectedTournaments as $tournament) {
            $dispatcher->dispatch(new TournamentUpdate($tournament));
        }

        // Inform about updated users bets
        foreach ($affectedUsers as $user) {
            $dispatcher->dispatch(new MeUpdate($user));
        }
    }

    /**
     * @param ApiEvent[] $apiEvents
     * @return Tournament[]|Collection
     */
    private function getAffectedTournaments(iterable $apiEvents): Collection
    {
        return collect($apiEvents)
            ->flatMap(fn(ApiEvent $apiEvent) => $apiEvent->tournamentEvents)
            ->map(fn(TournamentEvent $tournamentEvent) => $tournamentEvent->tournament)
            ->unique("id");
    }

    /**
     * @param TournamentBetEvent[] $betEvents
     * @return User[]|Collection
     */
    private function getAffectedUsers(iterable $betEvents): Collection
    {
        return collect($betEvents)
            ->map(fn(TournamentBetEvent $event) => $event->tournamentBet->tournamentPlayer->user)
            ->unique("id");
    }
}
