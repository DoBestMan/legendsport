<?php
namespace App\Tournament;

use App\Models\ApiEvent;
use App\Models\TournamentBet;
use App\Models\TournamentBetEvent;
use App\Models\TournamentEvent;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Collection;

class BetEvaluatorService
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
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

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $query = $queryBuilder
            ->select('te', 'ae')
            ->from(\App\Domain\TournamentEvent::class, 'te')
            ->join('te.apiEvent', 'ae')
            ->where('ae.id = ?1')
            ->getQuery();

        /** @var \App\Domain\TournamentEvent[] $results */
        $results = $query->setParameter(1, $apiEvent->id)->getResult();

        foreach ($results as $result) {
            $result->getApiEvent()->sync($apiEvent);
            $result->evaluate();
        }

        $this->entityManager->flush();

        $betEventsToEvaluate = $apiEvent->tournamentEvents
            ->flatMap(fn(TournamentEvent $tournamentEvent) => $tournamentEvent->tournamentBetEvents)
            ->map(fn(TournamentBetEvent $tournamentBetEvent) => $tournamentBetEvent->refresh());

        $betsToAward = $betEventsToEvaluate
            ->map(fn(TournamentBetEvent $betEvent) => $betEvent->tournamentBet->refresh())
            ->filter(fn(TournamentBet $tournamentBet) => $tournamentBet->isAwardable());

        /** @var TournamentBetEvent[]|Collection $betEventsToEvaluate */

        return [$betEventsToEvaluate, $betsToAward];
    }
}
