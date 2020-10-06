<?php

namespace App\Domain\Prizes;

use App\Domain\Bot;
use App\Domain\TournamentPlayer;

class PrizeMoneyCollection
{
    /** @var PrizeMoney[]  */
    private array $prizeMoney;

    public function __construct(PrizeMoney ...$prizeMoney)
    {
        $this->prizeMoney = $prizeMoney;
    }

    /** @return PrizeMoney[] */
    public function getPrizeMoney(): array
    {
        return $this->prizeMoney;
    }

    public function allocate(TournamentPlayer ...$players): array
    {
        $players = array_values($players);
        usort($players, fn (TournamentPlayer $a, TournamentPlayer $b) => -$a->getChips() <=> -$b->getChips());
        $payouts = [];

        $playersProcessed = 0;
        foreach ($this->prizeMoney as $prizeMoney) {
            for (null; $playersProcessed < $prizeMoney->getMaxPosition(); $playersProcessed++) {
                $player = $players[$playersProcessed];
                $player->getUser()->creditWinnings($prizeMoney->getPrizeMoney());
                $payouts[] = [$player->getUser() instanceof Bot, $prizeMoney->getPrizeMoney(), $player->getTournament()];
            }
        }

        return $payouts;
    }
}
