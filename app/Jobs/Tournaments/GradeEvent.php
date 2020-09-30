<?php

namespace App\Jobs\Tournaments;

use App\Jobs\Publishers\PublishTournamentUpdate;
use App\Jobs\Publishers\PublishUserUpdate;
use App\Domain\Tournament;
use App\Queue\Uniqueable;
use App\Tournament\TournamentCompletionService;
use Carbon\Carbon;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManager;
use Illuminate\Bus\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Psr\Log\LoggerInterface;

class GradeEvent implements ShouldQueue, Uniqueable
{
    public \DateTime $delay;
    private int $tournamentId;
    private int $tournamentEventId;

    public function __construct(int $tournamentId, int $tournamentEventId)
    {
        $this->delay = Carbon::now()->addSeconds(1);
        $this->tournamentId = $tournamentId;
        $this->tournamentEventId = $tournamentEventId;
    }

    public function handle(EntityManager $entityManager, Dispatcher $dispatcher, LoggerInterface $logger)
    {
        $logger->info(sprintf('Grading bets in Tournament: %s Tournament Event: %s', $this->tournamentId, $this->tournamentEventId));

        $entityManager->beginTransaction();
        /** @var Tournament $tournament */
        $tournament = $entityManager->getRepository(Tournament::class)
            ->find($this->tournamentId, LockMode::PESSIMISTIC_WRITE);
        $tournamentEvent = $tournament->getEvent($this->tournamentEventId);

        $tournamentUpdated = false;

        foreach ($tournamentEvent->getBets() as $bet) {
            $updated = $bet->evaluate();

            if ($updated) {
                $tournamentUpdated = true;
                $tournamentBet = $bet->getTournamentBet();
                $tournamentPlayer = $tournamentBet->getTournamentPlayer();
                $user = $tournamentPlayer->getUser();

                $logger->info(
                    sprintf('Bet %s was graded in Tournament: %s Tournament Event: %s', $bet->getId(), $this->tournamentId, $this->tournamentEventId),
                    [
                        'player_id' => $user->getId(),
                        'tournament_bet_id' => $tournamentBet->getId(),
                        'status' => $tournamentBet->getStatus(),
                        'chips_wager' => $tournamentBet->getChipsWager(),
                        'chips_win' => $tournamentBet->getChipsWon(),
                        'chips_now' => $tournamentPlayer->getChips(),
                    ]
                );

                $dispatcher->dispatch(new PublishUserUpdate($user->getId()));
            }
        }

        if ($tournamentUpdated) {
            $dispatcher->dispatch(new CheckForTournamentCompletion($tournament->getId()));
            $dispatcher->dispatch(new PublishTournamentUpdate($this->tournamentId));
        }

        $entityManager->flush();
        $entityManager->commit();
    }

    private function repr(...$params)
    {
        return sprintf('%s(%s)', static::class, implode(', ', $params));
    }

    public function uniqueable()
    {
        return hash('sha256', $this->repr($this->tournamentId, $this->tournamentEventId));
    }
}
