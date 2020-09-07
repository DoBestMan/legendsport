<?php
namespace App\Tournament;

use App\Betting\SportEventResult;
use App\Domain\ApiEvent;
use App\Domain\User;
use App\Domain\Tournament;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

class SportEventResultProcessor
{
    /** @var User[] */
    private array $usersUpdated = [];

    /** @var Tournament[] */
    private array $tournamentsUpdated = [];

    private LoggerInterface $logger;
    private EntityManager $entityManager;

    public function __construct(
        EntityManager $entityManager,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
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

        foreach($apiEvent->getTournamentEvents() as $tournamentEvent) {
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

                        $this->usersUpdated[$user->getId()] = $user;
                    }
                }
            }

            $tournament = $tournamentEvent->getTournament();
            $this->tournamentsUpdated[$tournament->getId()] = $tournament;
        }

        $this->entityManager->flush();
    }

    /**
     * @return Tournament[]
     */
    public function getTournamentsUpdated(): array
    {
        return $this->tournamentsUpdated;
    }

    /**
     * @return User[]
     */
    public function getUsersUpdated(): array
    {
        return $this->usersUpdated;
    }
}
