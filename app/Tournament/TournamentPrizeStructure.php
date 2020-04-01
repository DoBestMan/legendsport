<?php
namespace App\Tournament;

use Decimal\Decimal;

class TournamentPrizeStructure
{
    private array $prizes;

    private Decimal $money;
    private int $playersCount;

    public function __construct(int $money, int $playersCount)
    {
        $this->money = new Decimal($money);
        $this->playersCount = $playersCount;
        $this->initPrizes();
    }

    /**
     * @return Prize[]
     */
    public function getPrizes(): array
    {
        /** @var PrizeCollection $prizeCollection */
        $prizeCollection = collect($this->prizes)->first(
            fn(PrizeCollection $prizes) => $prizes->getMaxPlayers() >= $this->playersCount,
        );

        return collect($prizeCollection->getPrizes())
            ->map(
                fn(Prize $prize) => new Prize(
                    $prize->getMaxPosition(),
                    $this->money * ($prize->getPrize() / 100),
                ),
            )
            ->all();
    }

    private function initPrizes(): void
    {
        $this->prizes = [
            new PrizeCollection(1, []),
            new PrizeCollection(2, [new Prize(1, 100)]),
            new PrizeCollection(10, [new Prize(1, 50), new Prize(2, 30), new Prize(3, 20)]),
            new PrizeCollection(18, [
                new Prize(1, 40),
                new Prize(2, 30),
                new Prize(3, 20),
                new Prize(4, 10),
            ]),
            new PrizeCollection(27, [
                new Prize(1, 40),
                new Prize(2, 23),
                new Prize(3, 16),
                new Prize(4, 12),
                new Prize(5, 9),
            ]),
            new PrizeCollection(36, [
                new Prize(1, 33),
                new Prize(2, 20),
                new Prize(3, 15),
                new Prize(4, 11),
                new Prize(5, 8),
                new Prize(6, 7),
                new Prize(7, 6),
            ]),
            new PrizeCollection(50, [
                new Prize(1, 29),
                new Prize(2, 18),
                new Prize(3, 13),
                new Prize(4, 10),
                new Prize(5, 8),
                new Prize(6, 7),
                new Prize(7, 6),
                new Prize(8, 5),
                new Prize(9, 4),
            ]),
            new PrizeCollection(66, [
                new Prize(1, 26),
                new Prize(2, "16.5"),
                new Prize(3, 12),
                new Prize(4, "9.5"),
                new Prize(5, 8),
                new Prize(6, "6.5"),
                new Prize(7, 5),
                new Prize(8, 4),
                new Prize(9, "3.5"),
                new Prize(12, 3),
            ]),
            new PrizeCollection(83, [
                new Prize(1, "25.5"),
                new Prize(2, 16),
                new Prize(3, "11.5"),
                new Prize(4, 9),
                new Prize(5, "7.5"),
                new Prize(6, 6),
                new Prize(7, "4.5"),
                new Prize(8, "3.5"),
                new Prize(9, 3),
                new Prize(12, "2.5"),
                new Prize(15, 2),
            ]),
            new PrizeCollection(117, [
                new Prize(1, 25),
                new Prize(2, "15.5"),
                new Prize(3, 11),
                new Prize(4, "8.5"),
                new Prize(5, 7),
                new Prize(6, "5.5"),
                new Prize(7, 4),
                new Prize(8, 3),
                new Prize(9, "2.5"),
                new Prize(12, "2.2"),
                new Prize(15, 2),
                new Prize(18, "1.8"),
            ]),
            // TODO Fill it
        ];
    }
}
