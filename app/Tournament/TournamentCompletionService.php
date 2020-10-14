<?php
namespace App\Tournament;

use App\Domain\Tournament as TournamentEntity;
use App\Domain\TournamentPayout;
use App\Mail\TournamentWin;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManager;
use Illuminate\Mail\MailManager;

class TournamentCompletionService
{
    private EntityManager $entityManager;
    private MailManager $mail;

    public function __construct(EntityManager $entityManager, MailManager $mail)
    {
        $this->entityManager = $entityManager;
        $this->mail = $mail;
    }

    public function updateState(int $tournamentId): bool
    {
        $this->entityManager->beginTransaction();
        /** @var TournamentEntity $tournament */
        $tournament = $this->entityManager->find(TournamentEntity::class, $tournamentId, LockMode::PESSIMISTIC_WRITE);

        if ($tournament->isFinished() || !$tournament->isReadyForCompletion()) {
            $this->entityManager->commit();
            return false;
        }

        $tournament->complete();
        /** @var TournamentPayout[] $payouts */
        $payouts = $tournament->getPrizeMoney()->allocate(...$tournament->getPlayers()->toArray());
        foreach ($payouts as $payout) {
            $this->entityManager->persist($payout);

            if (!$payout->isBot()) {
                $this->mail->to($payout->getUser()->getEmail())->send(
                    new TournamentWin(
                        $payout->getUser()->getFullname(),
                        $payout->getTournament()->getName(),
                        $payout->getRank(),
                        $payout->getPayout()
                    )
                );
            }
        }

        $this->entityManager->flush();
        $this->entityManager->commit();

        return true;
    }
}
