<?php

namespace App\BotPlayers;

use App\Domain\BetTypes\MoneyLineAway;
use App\Domain\BetTypes\MoneyLineHome;
use App\Domain\BetTypes\SpreadAway;
use App\Domain\BetTypes\SpreadHome;
use App\Domain\BetTypes\TotalOver;
use App\Domain\BetTypes\TotalUnder;
use App\Domain\Bot;
use App\Domain\Tournament;
use App\Domain\TournamentBet;
use App\Domain\TournamentEvent;
use App\Repository\RepositoryManager;
use Carbon\Carbon;
use Doctrine\Common\Collections\Criteria;
use Ramsey\Uuid\Type\Decimal;
use function foo\func;

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

        $tournamentIdsAffected = [];
        $tournamentRepository = $this->repositoryManager->get(Tournament::class);
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->notIn('state', ['Cancel', 'Completed']));
        $criteria->andWhere(Criteria::expr()->neq('registrationDeadline', null));
        /** @var Tournament[] $tournaments */
        $tournaments = $tournamentRepository->matching($criteria);

        foreach ($tournaments as $tournament) {
            $events = $tournament->getEvents()->filter(function (TournamentEvent $tournamentEvent) {
                return $tournamentEvent->getApiEvent()->isUpcoming() && !empty($tournamentEvent->getApiEvent()->getOddTypes());
            })->toArray();

            if (count($events)) {
                /** @var Bot[] $botsWithChips */
                $botsWithChips = $this->botFinder->withChipsLeft($tournament);

                $maxBetOptions = 0;
                foreach ($events as $event) {
                    $maxBetOptions += count($event->getApiEvent()->getOddTypes());
                }

                foreach ($botsWithChips as $bot) {
                    /** @var Bot $bot */
                    $tournamentPlayer = $bot->getTournamentPlayer($tournament);
                    $chips = intval($tournamentPlayer->getChips() / 100);
                    $straightBetChips = intval(rand(30, 40) * $chips * 0.01);

                    $betsToPlace = min(rand(1, 8), $maxBetOptions);

                    $wagersToPlace = $this->calculateWagers($straightBetChips, $betsToPlace);

                    $betsPlaced = [];

                    foreach ($wagersToPlace as $wager) {
                        if ($wager === 0) {
                            continue;
                        }

                        $betPlaced = false;
                        do {
                            $event = $events[array_rand($events, 1)];
                            $betTypes = $event->getApiEvent()->getOddTypes();
                            $betType = $betTypes[array_rand($betTypes, 1)];

                            if (!isset($betsPlaced[$event->getId()][$betType])) {
                                $betsPlaced[$event->getId()][$betType] = true;
                                $tournament->placeStraightBet($tournamentPlayer, $event, $betType, $wager * 100);
                                $betPlaced = true;
                            }
                        } while (!$betPlaced);
                    }
                }
            }
            $tournamentRepository->commit();
        }
        return $tournamentIdsAffected;
    }

    private function calculateWagers(int $straightBetChips, int $betsToPlace): array
    {
        $wagers = [0, $straightBetChips];
        for ($i = 0; $i < $betsToPlace - 2; $i++) {
            $wagers[] = intval(rand(0, $straightBetChips));
        }

        sort($wagers);
        $wagersToPlace = [];
        foreach ($wagers as $i => $wager) {
            if ($i === 0) {
                continue;
            }
            $wagersToPlace[] = $wager - $wagers[$i - 1];
        }

        return $wagersToPlace;
    }
}
