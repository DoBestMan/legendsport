<?php

namespace App\BotPlayers;

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
        $straightBets = new StraightBets($wagerCalculator);
        $twoParlayBets = new ParlayBets($wagerCalculator, 2);
        $threeParlayBets = new ParlayBets($wagerCalculator, 3);

        $tournamentIdsAffected = [];
        $tournamentRepository = $this->repositoryManager->get(Tournament::class);
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
                    $hundredChips = intval($tournamentPlayer->getChips() / 100);

                    $straightBetChips = intval(rand(30, 40) * $hundredChips * 0.01);
                    $straightBets->placeBets($tournament, $tournamentPlayer, $straightBetChips);

                    $twoParlayChips = intval(rand(30, 40) * $hundredChips * 0.01);
                    $twoParlayBets->placeBets($tournament, $tournamentPlayer, $twoParlayChips);

                    $threeParleyChips = $hundredChips - ($straightBetChips + $twoParlayChips);
                    $threeParlayBets->placeBets($tournament, $tournamentPlayer, $threeParleyChips);
                }
            }
            $tournamentRepository->commit();
        }
        return $tournamentIdsAffected;
    }
}
