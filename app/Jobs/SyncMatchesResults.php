<?php
namespace App\Jobs;

use App\Betting\BettingProvider;
use App\Betting\TimeStatus;
use App\Models\ApiEvent;
use App\Models\TournamentBetEvent;
use App\Models\User;
use App\Tournament\MatchEvaluationService;
use App\User\MeUpdate;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\DatabaseManager;
use Psr\Log\LoggerInterface;

final class SyncMatchesResults
{
    public function handle(
        BettingProvider $bettingProvider,
        MatchEvaluationService $matchEvaluationService,
        LoggerInterface $logger,
        DatabaseManager $databaseManager,
        Dispatcher $dispatcher
    ): void {
        $evaluatedBetEvents = collect();

        foreach ($bettingProvider->getResults() as $result) {
            /** @var ApiEvent $apiEvent */
            $apiEvent = ApiEvent::with([
                "tournamentEvents.tournamentBetEvents.tournamentBet.tournamentPlayer",
            ])
                ->where("api_id", $result->getExternalEventId())
                ->first();

            if (!$apiEvent || $apiEvent->time_status->equals(TimeStatus::ENDED())) {
                continue;
            }

            $tournamentBetEvents = $databaseManager->transaction(function () use (
                $apiEvent,
                $result,
                $logger,
                $matchEvaluationService
            ) {
                $apiEvent->score_home = $result->getHome();
                $apiEvent->score_away = $result->getAway();
                $apiEvent->time_status = $result->getTimeStatus();
                $apiEvent->save();

                $logger->info("Api event was updated.", [
                    "api_event_id" => $apiEvent->id,
                    "api_event_external_id" => $apiEvent->api_id,
                    "score_home" => $result->getHome(),
                    "score_away" => $result->getAway(),
                    "time_status" => $result->getTimeStatus(),
                ]);

                return $result->getTimeStatus()->equals(TimeStatus::ENDED())
                    ? $matchEvaluationService->evaluateBets($apiEvent)
                    : [];
            });

            $evaluatedBetEvents->push(...$tournamentBetEvents);
        }

        // Inform users about evaluation
        $evaluatedBetEvents
            ->map(fn(TournamentBetEvent $event) => $event->tournamentBet->tournamentPlayer->user)
            ->unique("id")
            ->each(fn(User $user) => $dispatcher->dispatch(new MeUpdate($user)));
    }
}
