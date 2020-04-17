<?php
namespace App\Tournament;

use App\Betting\TimeStatus;
use App\Models\ApiEvent;
use App\Models\TournamentBet;
use App\Models\TournamentBetEvent;
use App\Models\TournamentEvent;
use App\Tournament\Enums\BetStatus;
use App\Tournament\Evaluation\EvaluatorFactory;
use Illuminate\Support\Collection;

class BetEvaluatorService
{
    private EvaluatorFactory $evaluatorFactory;

    public function __construct(EvaluatorFactory $evaluatorFactory)
    {
        $this->evaluatorFactory = $evaluatorFactory;
    }

    /**
     * Evaluates bets based on finished api event.
     * Returns evaluated bet events and awarded bets.
     *
     * @param ApiEvent $apiEvent
     * @return array|array[]
     */
    public function evaluate(ApiEvent $apiEvent): array
    {
        if (!$apiEvent->isFinished()) {
            return [[], []];
        }

        /** @var TournamentBetEvent[]|Collection $betEventsToEvaluate */
        $betEventsToEvaluate = $apiEvent->tournamentEvents
            ->flatMap(fn(TournamentEvent $tournamentEvent) => $tournamentEvent->tournamentBetEvents)
            // Do not evaluate bet if it is graded already.
            // It means that bet was evaluated before.
            ->filter(fn(TournamentBetEvent $betEvent) => !$betEvent->tournamentBet->isGraded());

        foreach ($betEventsToEvaluate as $tournamentBetEvent) {
            $tournamentBetEvent->status = $this->evaluateBetStatus($tournamentBetEvent);
            $tournamentBetEvent->save();
        }

        /** @var TournamentBet[]|Collection $betsToAward */
        $betsToAward = $betEventsToEvaluate
            // TournamentBet relations are already cached. Let's reload a model,
            // so that we know its latest state.
            ->map(fn(TournamentBetEvent $betEvent) => $betEvent->tournamentBet->refresh())
            ->filter(fn(TournamentBet $tournamentBet) => $tournamentBet->isAwardable());

        foreach ($betsToAward as $tournamentBet) {
            $player = $tournamentBet->tournamentPlayer;
            $player->chips += $tournamentBet->chips_wager + $tournamentBet->chips_win;
            $player->save();
        }

        return [$betEventsToEvaluate, $betsToAward];
    }

    private function evaluateBetStatus(TournamentBetEvent $tournamentBetEvent): BetStatus
    {
        $apiEvent = $tournamentBetEvent->tournamentEvent->apiEvent;

        if ($apiEvent->time_status->equals(TimeStatus::CANCELED())) {
            return BetStatus::PUSH();
        }

        return $this->evaluatorFactory
            ->create($tournamentBetEvent->type)
            ->evaluate($apiEvent, $tournamentBetEvent->handicap);
    }
}
