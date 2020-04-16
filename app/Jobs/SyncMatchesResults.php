<?php
namespace App\Jobs;

use App\Betting\BettingProvider;
use App\Betting\TimeStatus;
use App\Models\ApiEvent;
use App\Models\Tournament;
use App\Models\TournamentBetEvent;
use App\Models\TournamentEvent;
use App\Models\User;
use App\Tournament\Events\TournamentUpdate;
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
        $updatedApiEvents = collect();

        foreach ($bettingProvider->getResults() as $result) {
            /** @var ApiEvent $apiEvent */
            $apiEvent = ApiEvent::with([
                "tournamentEvents.tournamentBetEvents.tournamentBet.tournamentPlayer",
            ])
                ->where("api_id", $result->getExternalEventId())
                ->first();

            if (!$apiEvent || $apiEvent->isFinished()) {
                continue;
            }

            $databaseManager->transaction(function () use (
                $apiEvent,
                $result,
                $logger,
                $matchEvaluationService,
                $evaluatedBetEvents,
                $updatedApiEvents
            ) {
                $apiEvent->score_home = $result->getHome();
                $apiEvent->score_away = $result->getAway();
                $apiEvent->time_status = $result->getTimeStatus();

                if ($apiEvent->isDirty()) {
                    $apiEvent->save();
                    $updatedApiEvents->push($apiEvent);
                    $logger->info("Api event was updated.", [
                        "api_event_id" => $apiEvent->id,
                        "api_event_external_id" => $apiEvent->api_id,
                        "score_home" => $result->getHome(),
                        "score_away" => $result->getAway(),
                        "time_status" => $result->getTimeStatus(),
                    ]);
                }

                if ($result->getTimeStatus()->equals(TimeStatus::ENDED())) {
                    $tournamentBetEvent = $matchEvaluationService->evaluateBets($apiEvent);
                    $evaluatedBetEvents->push(...$tournamentBetEvent);
                }
            });
        }

        // Inform about tournaments results update
        $updatedApiEvents
            ->flatMap(fn(ApiEvent $apiEvent) => $apiEvent->tournamentEvents)
            ->map(fn(TournamentEvent $tournamentEvent) => $tournamentEvent->tournament)
            ->unique("id")
            ->each(fn(Tournament $item) => $dispatcher->dispatch(new TournamentUpdate($item)));

        // Inform about users bets update
        $evaluatedBetEvents
            ->map(fn(TournamentBetEvent $event) => $event->tournamentBet->tournamentPlayer->user)
            ->unique("id")
            ->each(fn(User $user) => $dispatcher->dispatch(new MeUpdate($user)));
    }
}
