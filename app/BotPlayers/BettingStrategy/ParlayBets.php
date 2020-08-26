<?php

namespace App\BotPlayers\BettingStrategy;

use App\Domain\BetItem;
use App\Domain\Tournament;
use App\Domain\TournamentEvent;
use App\Domain\TournamentPlayer;

class ParlayBets implements BettingStrategy
{
    private int $maxBets;
    private int $minBets;
    private WagerCalculator $wagerCalculator;
    private $parlaySize;

    public function __construct(WagerCalculator $wagerCalculator, int $parlaySize, int $minBets = 1, int $maxBets = 4)
    {
        $this->maxBets = $maxBets;
        $this->minBets = $minBets;
        $this->wagerCalculator = $wagerCalculator;
        $this->parlaySize = $parlaySize;
    }

    public function placeBets(Tournament $tournament, TournamentPlayer $tournamentPlayer, int $hundredChipsToWager, int $remainder = 0): bool
    {
        if ($hundredChipsToWager + $remainder === 0) {
            return false;
        }

        /** @var TournamentEvent[] $events */
        $events = $tournament->getBettableEvents()->toArray();

        $maxBetOptions = 0;
        foreach ($events as $event) {
            $maxBetOptions += count($event->getApiEvent()->getOddTypes());
        }

        $betsToPlace = min(rand($this->minBets, $this->maxBets), $maxBetOptions);
        if ($betsToPlace === 0) {
            return false;
        }

        $wagersToPlace = $this->wagerCalculator->calculateWagers($hundredChipsToWager, $betsToPlace);
        if (count($wagersToPlace)) {
            $wagersToPlace[0] += $remainder;
        }
        $betsPlaced = [];

        foreach ($wagersToPlace as $wager) {
            if ($wager === 0) {
                continue;
            }

            $betPlaced = false;
            do {
                $bets = $this->chooseBets($events);
                $betIdentifier = $this->calculateBetIdentifier($bets);

                if (!isset($betsPlaced[$betIdentifier])) {
                    $betsPlaced[$betIdentifier] = true;

                    $tournament->placeParlayBet($tournamentPlayer, $wager * 100, ...$bets);
                    $betPlaced = true;
                }
            } while (!$betPlaced);
        }

        return true;
    }

    private function chooseBets(array $events): array
    {
        $betsPicked = [];
        $correlations = [];
        do {
            $event = $events[array_rand($events, 1)];
            $betTypes = $event->getApiEvent()->getOddTypes();
            $betType = $betTypes[array_rand($betTypes, 1)];

            $betItem = new BetItem($betType, $event);
            if (isset($correlations[$betItem->getCorrelationIdentifier()])) {
                continue;
            }

            $betsPicked[] = $betItem;
            $correlations[$betItem->getCorrelationIdentifier()] = true;

        } while (count($betsPicked) < $this->parlaySize);

        return $betsPicked;
    }

    private function calculateBetIdentifier(array $bets): string
    {
        $a = [];
        foreach ($bets as $bet) {
            $a[] = $bet->getIdentifier();
        }

        sort($a);
        return implode('#', $a);
    }
}
