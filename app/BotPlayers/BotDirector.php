<?php

namespace App\BotPlayers;

use App\BotPlayers\BettingPlan\BettingPlan;
use App\BotPlayers\BettingPlan\BettingPlanCollection;
use App\BotPlayers\BettingStrategy\BettingStrategy;
use App\BotPlayers\BettingStrategy\StraightBets;
use App\BotPlayers\BettingStrategy\ParlayBets;
use App\BotPlayers\BettingStrategy\WagerCalculator;
use App\Domain\Bot;
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
        $tournamentRepository->startTransaction();

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

    public function placeBets(): array
    {
        srand(random_int(0, \PHP_INT_MAX));

        $wagerCalculator = new WagerCalculator();
        $bettingPlans = new BettingPlanCollection($wagerCalculator, $this->aggressivePlan());

        $tournamentIdsAffected = [];
        $tournamentRepository = $this->repositoryManager->get(Tournament::class);
        $tournamentRepository->startTransaction();

        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->notIn('state', ['Cancel', 'Completed']));
        $criteria->andWhere(Criteria::expr()->neq('registrationDeadline', null));
        /** @var Tournament[] $tournaments */
        $tournaments = $tournamentRepository->matching($criteria);

        foreach ($tournaments as $tournament) {
            $events = $tournament->getBettableEvents();

            if ($events->count() > 0) {
                /** @var Bot[] $botsWithChips */
                $botsWithChips = $this->botFinder->withChipsLeft($tournament);

                foreach ($botsWithChips as $bot) {
                    $tournamentPlayer = $bot->getTournamentPlayer($tournament);
                    $chips = $tournamentPlayer->getChips();
                    $hundredChips = intval($tournamentPlayer->getChips() / 100);

                    foreach ($bettingPlans as $bettingPlan) {
                        /** @var BettingPlan $bettingPlan */
                        $betChips = intval($bettingPlan->getChipsPercent() * $hundredChips);
                        $remainder = 0;

                        if ($bettingPlans->isLast()) {
                            $betChips = intval($chips / 100);
                            $remainder = $chips - $betChips * 100;
                        }

                        $chips -= $betChips * 100;

                        $placed = $bettingPlan->getStrategy()->placeBets($tournament, $tournamentPlayer, $betChips, $remainder);

                        if (!$placed) {
                            $chips += $betChips * 100;
                        }
                    }
                }
            }
            $tournamentRepository->commit();
        }
        return $tournamentIdsAffected;
    }

    private function aggressivePlan()
    {
        return [
            ['parlaySize' => 1, 'minBets' => 1, 'maxBets' => 2, 'minChipsPercent' => 20, 'maxChipsPercent' => 30],
            ['parlaySize' => 2, 'minBets' => 1, 'maxBets' => 2, 'minChipsPercent' => 20, 'maxChipsPercent' => 30],
            ['parlaySize' => 3, 'minBets' => 1, 'maxBets' => 1, 'minChipsPercent' => 20, 'maxChipsPercent' => 40],
            ['parlaySize' => 4, 'minBets' => 1, 'maxBets' => 1, 'minChipsPercent' => 20, 'maxChipsPercent' => 40],
        ];
    }

    private function steadyPlan()
    {
        return [
            ['parlaySize' => 1, 'minBets' => 1, 'maxBets' => 8, 'minChipsPercent' => 30, 'maxChipsPercent' => 40],
            ['parlaySize' => 2, 'minBets' => 1, 'maxBets' => 4, 'minChipsPercent' => 30, 'maxChipsPercent' => 40],
            ['parlaySize' => 3, 'minBets' => 1, 'maxBets' => 4, 'minChipsPercent' => 30, 'maxChipsPercent' => 40],
        ];
    }
}
