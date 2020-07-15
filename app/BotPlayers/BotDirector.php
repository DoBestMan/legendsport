<?php

namespace App\BotPlayers;

use App\Domain\Tournament;
use App\Repository\RepositoryManager;
use Carbon\Carbon;
use Doctrine\Common\Collections\Criteria;

class BotDirector
{
    private RepositoryManager $repositoryManager;
    private BotFinder $botFinder;

    public function __construct(RepositoryManager $repositoryManager, BotFinder $botFinder)
    {
        $this->repositoryManager = $repositoryManager;
        $this->botFinder = $botFinder;
    }

    public function joinTournaments(): array
    {
        $tournamentIdsAffected = [];
        $tournamentRepository = $this->repositoryManager->get(Tournament::class);
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('state', 'registering'));
        $criteria->andWhere(Criteria::expr()->neq('registrationDeadline', null));
        /** @var Tournament[] $tournaments */
        $tournaments = $tournamentRepository->matching($criteria);

        foreach ($tournaments as $tournament) {
            $botsToRegister = $tournament->getBotsToRegister();
            $minsToStart = Carbon::now()->diffInMinutes($tournament->getRegistrationDeadline());
            $botsToRegisterNow = min(round($botsToRegister/max(1, $minsToStart - 4)), 1000);

            printf("total: %d, time remaining: %d, creating now: %d\n", $botsToRegister, $minsToStart, $botsToRegisterNow);

            if ($botsToRegisterNow > 0) {
                $availableBots = $this->botFinder->notInTournament($tournament, $botsToRegisterNow);
                $foundBots = count($availableBots);

                if ($foundBots < $botsToRegisterNow) {
                    $availableBots = array_merge($availableBots, $this->botFinder->createBots($botsToRegisterNow - $foundBots));
                }

                foreach ($availableBots as $bot) {
                    $tournament->registerBot($bot);
                }

                $tournamentRepository->commit();
                $tournamentIdsAffected[] = $tournament->getId();
            }
        }

        return $tournamentIdsAffected;
    }
}
