<?php
namespace App\Tournament;

use App\Domain\Tournament as TournamentEntity;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManager;

class TournamentCompletionService
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function updateState(int $tournamentId): bool
    {
        $this->entityManager->beginTransaction();
        /** @var TournamentEntity $tournament */
        $tournament = $this->entityManager->find(TournamentEntity::class, $tournamentId, LockMode::PESSIMISTIC_WRITE);

        if ($tournament->isFinished() || !$tournament->isReadyForCompletion()) {
            return false;
        }

        $tournament->complete();
        $payouts = $tournament->getPrizeMoney()->allocate(...$tournament->getPlayers()->toArray());
        foreach ($payouts as $payout) {
            $this->entityManager->persist($payout);
        }

        $this->entityManager->flush();
        $this->entityManager->commit();

        return true;
    }
}
