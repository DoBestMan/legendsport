<?php
namespace App\Tournament;

use App\Betting\SportEventResult;
use App\Models\ApiEvent;
use App\Models\TournamentBet;
use App\Models\TournamentBetEvent;
use Illuminate\Database\DatabaseManager;
use Psr\Log\LoggerInterface;

class SportEventResultProcessor
{
    /** @var ApiEvent[] */
    private array $processedApiEvents = [];

    /** @var TournamentBetEvent[] */
    private array $processedBetEvents = [];

    /** @var TournamentBet[] */
    private array $awardedBets = [];

    private DatabaseManager $databaseManager;
    private BetEvaluatorService $betEvaluator;
    private LoggerInterface $logger;

    public function __construct(
        DatabaseManager $databaseManager,
        BetEvaluatorService $betEvaluator,
        LoggerInterface $logger
    ) {
        $this->databaseManager = $databaseManager;
        $this->betEvaluator = $betEvaluator;
        $this->logger = $logger;
    }

    /**
     * @param SportEventResult[] $results
     */
    public function processMultiple(iterable $results): void
    {
        foreach ($results as $result) {
            $this->process($result);
        }
    }

    public function process(SportEventResult $result): void
    {
        /** @var ApiEvent $apiEvent */
        $apiEvent = ApiEvent::with([
            "tournamentEvents.tournamentBetEvents.tournamentBet.tournamentPlayer",
        ])
            ->where("api_id", $result->getExternalEventId())
            ->first();

        if (!$apiEvent || $apiEvent->isFinished()) {
            return;
        }

        $apiEvent->score_home = $result->getHome();
        $apiEvent->score_away = $result->getAway();
        $apiEvent->time_status = $result->getTimeStatus();

        if (!$apiEvent->isDirty()) {
            return;
        }

        [$processedBetEvents, $awardedBets] = $this->databaseManager->transaction(function () use (
            $apiEvent
        ) {
            $apiEvent->save();
            return $this->betEvaluator->evaluate($apiEvent);
        });

        $this->logger->info("Api event has been updated.", [
            "api_event_id" => $apiEvent->id,
            "api_event_external_id" => $apiEvent->api_id,
            "score_home" => $apiEvent->score_home,
            "score_away" => $apiEvent->score_away,
            "time_status" => $apiEvent->time_status,
        ]);

        foreach ($awardedBets as $tournamentBet) {
            $this->logger->info("Player was awarded chips for the bet.", [
                "player_id" => $tournamentBet->tournamentPlayer->id,
                "tournament_bet_id" => $tournamentBet->id,
                "status" => $tournamentBet->status,
                "chips_wager" => $tournamentBet->chips_wager,
                "chips_win" => $tournamentBet->chips_win,
            ]);
        }

        $this->processedApiEvents[] = $apiEvent;
        array_push($this->processedBetEvents, ...$processedBetEvents);
        array_push($this->awardedBets, ...$awardedBets);
    }

    /**
     * @return ApiEvent[]
     */
    public function getProcessedApiEvents(): array
    {
        return $this->processedApiEvents;
    }

    /**
     * @return TournamentBetEvent[]
     */
    public function getProcessedBetEvents(): array
    {
        return $this->processedBetEvents;
    }

    /**
     * @return TournamentBet[]
     */
    public function getAwardedBets(): array
    {
        return $this->awardedBets;
    }
}
