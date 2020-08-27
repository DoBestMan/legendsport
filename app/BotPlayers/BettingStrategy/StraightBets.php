<?php

namespace App\BotPlayers\BettingStrategy;

use App\Domain\BetItem;
use App\Domain\Tournament;
use App\Domain\TournamentPlayer;

class StraightBets implements BettingStrategy
{
    private int $maxBets;
    private int $minBets;
    private WagerCalculator $wagerCalculator;

    public function __construct(WagerCalculator $wagerCalculator, int $minBets = 1, int $maxBets = 8)
    {
        $this->wagerCalculator = $wagerCalculator;
        $this->maxBets = $maxBets;
        $this->minBets = $minBets;
    }

    public function placeBets(Tournament $tournament, TournamentPlayer $tournamentPlayer, int $hundredChipsToWager, int $remainder = 0): bool
    {
        if ($hundredChipsToWager + $remainder === 0) {
            return false;
        }

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
                $event = $events[array_rand($events, 1)];
                $betTypes = $event->getApiEvent()->getOddTypes();
                $betType = $betTypes[array_rand($betTypes, 1)];

                if (!isset($betsPlaced[$event->getId()][$betType])) {
                    $betsPlaced[$event->getId()][$betType] = true;
                    $betItem = new BetItem($betType, $event);
                    $tournament->placeStraightBet($tournamentPlayer, $wager * 100, $betItem);
                    $betPlaced = true;
                }
            } while (!$betPlaced);
        }

        return true;
    }
}
