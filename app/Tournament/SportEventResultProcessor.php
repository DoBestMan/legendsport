<?php
namespace App\Tournament;

use App\Jobs\Tournaments\CheckForTournamentCompletion;
use App\Betting\SportEventResult;
use App\Domain\ApiEvent;
use App\Jobs\Publishers\PublishTournamentUpdate;
use App\Jobs\Publishers\PublishUserUpdate;
use Doctrine\ORM\EntityManager;
use Illuminate\Bus\Dispatcher;
use Psr\Log\LoggerInterface;

class SportEventResultProcessor
{
    private LoggerInterface $logger;
    private EntityManager $entityManager;
    private Dispatcher $dispatcher;

    public function __construct(
        EntityManager $entityManager,
        LoggerInterface $logger,
        Dispatcher $dispatcher
    ) {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param SportEventResult[] $results
     */
    public function processMultiple(iterable $results): void
    {
        $this->entityManager->beginTransaction();
        foreach ($results as $result) {
            $this->process($result);
        }
        $this->entityManager->commit();
    }

    public function process(SportEventResult $result): void
    {
        /** @var ApiEvent $apiEvent */
        $apiEvent = $this->entityManager->getRepository(ApiEvent::class)
            ->findOneBy(['apiId' => $result->getExternalEventId(), 'provider' => $result->getProvider()]);

        if ($apiEvent === null || $apiEvent->isFinished()) {
            return;
        }

        $updated = $apiEvent->result($result);

        if (!$updated) {
            return;
        }

        $this->logger->info("Api event has been updated.", [
            "api_event_id" => $apiEvent->getId(),
            "api_event_external_id" => $apiEvent->getApiId(),
            "score_home" => $apiEvent->getScoreHome(),
            "score_away" => $apiEvent->getScoreAway(),
            "time_status" => $apiEvent->getTimeStatus(),
        ]);

        foreach ($apiEvent->getTournamentEvents() as $tournamentEvent) {
            if ($apiEvent->isFinished()) {
                foreach ($tournamentEvent->getBets() as $bet) {
                    $updated = $bet->evaluate();

                    if ($updated) {
                        $tournamentBet = $bet->getTournamentBet();
                        $user = $tournamentBet->getTournamentPlayer()->getUser();

                        $this->logger->info("Bet was graded", [
                            "player_id" => $user->getId(),
                            "tournament_bet_id" => $tournamentBet->getId(),
                            "status" => $tournamentBet->getStatus(),
                            "chips_wager" => $tournamentBet->getChipsWager(),
                            "chips_win" => $tournamentBet->getChipsWon(),
                        ]);

                        $this->dispatcher->dispatch(new PublishUserUpdate($user->getId()));
                    }
                }
            }

            $tournament = $tournamentEvent->getTournament();
            $this->dispatcher->dispatch(new CheckForTournamentCompletion($tournament->getId()));
            $this->dispatcher->dispatch(new PublishTournamentUpdate($tournament->getId()));
        }

        $this->entityManager->flush();
    }
}
