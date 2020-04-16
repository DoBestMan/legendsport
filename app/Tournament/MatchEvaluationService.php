<?php
namespace App\Tournament;

use App\Betting\TimeStatus;
use App\Models\ApiEvent;
use App\Models\TournamentBetEvent;
use App\Tournament\Enums\BetStatus;
use App\Tournament\Evaluation\EvaluatorFactory;
use Psr\Log\LoggerInterface;

class MatchEvaluationService
{
    private LoggerInterface $logger;
    private EvaluatorFactory $evaluatorFactory;

    public function __construct(EvaluatorFactory $evaluatorFactory, LoggerInterface $logger)
    {
        $this->evaluatorFactory = $evaluatorFactory;
        $this->logger = $logger;
    }

    /**
     * @param ApiEvent $apiEvent
     * @return TournamentBetEvent[]
     */
    public function evaluateBets(ApiEvent $apiEvent): array
    {
        if (!$apiEvent->isFinished()) {
            return [];
        }

        $evaluatedTournamentEvents = [];

        foreach ($apiEvent->tournamentEvents as $tournamentEvent) {
            foreach ($tournamentEvent->tournamentBetEvents as $tournamentBetEvent) {
                $tournamentBet = $tournamentBetEvent->tournamentBet;

                // Do not evaluate bet if it is graded already.
                // It means that bet was evaluated before.
                if ($tournamentBet->isGraded()) {
                    continue;
                }

                $tournamentBetEvent->status = $this->evaluateBetStatus($tournamentBetEvent);
                $tournamentBetEvent->save();

                // TournamentBet relations are already cached. Let's reload a model,
                // so that we know its latest state.
                $tournamentBet = $tournamentBet->fresh();

                if (
                    $tournamentBet->status->equals(BetStatus::WIN()) ||
                    $tournamentBet->status->equals(BetStatus::PUSH())
                ) {
                    $player = $tournamentBet->tournamentPlayer;
                    $player->chips += $tournamentBet->chips_wager + $tournamentBet->chips_win;
                    $player->save();

                    $this->logger->info("Player was awarded chips on bet evaluation.", [
                        "player_id" => $player->id,
                        "tournament_bet_id" => $tournamentBet->id,
                        "status" => $tournamentBet->status,
                        "chips_wager" => $tournamentBet->chips_wager,
                        "chips_win" => $tournamentBet->chips_win,
                    ]);
                }

                $evaluatedTournamentEvents[] = $tournamentBetEvent;
            }
        }

        return $evaluatedTournamentEvents;
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
