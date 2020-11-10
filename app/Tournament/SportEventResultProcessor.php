<?php
namespace App\Tournament;

use App\Jobs\Tournaments\CheckForTournamentCompletion;
use App\Betting\SportEvent\Result;
use App\Domain\ApiEvent;
use App\Jobs\Publishers\PublishTournamentUpdate;
use App\Jobs\Publishers\PublishUserUpdate;
use App\Jobs\Tournaments\GradeEvent;
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
     * @param Result[] $results
     */
    public function processMultiple(iterable $results): void
    {
        $this->entityManager->beginTransaction();
        foreach ($results as $result) {
            $this->process($result);
        }
        $this->entityManager->commit();
    }

    public function process(Result $result): void
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
            $tournament = $tournamentEvent->getTournament();

            if ($apiEvent->isFinished()) {
                $this->dispatcher->dispatch(new GradeEvent($tournament->getId(), $tournamentEvent->getId()));
            }

            $this->dispatcher->dispatch(new PublishTournamentUpdate($tournament->getId()));
        }

        $this->entityManager->flush();
    }
}
