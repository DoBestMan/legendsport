<?php
namespace App\Domain\Prizes;

class PrizeCollection
{
    /** @var Prize[] */
    private array $prizes;

    public function __construct(Prize ...$prizes)
    {
        $this->prizes = $prizes;
    }

    /** @return Prize[] */
    public function getPrizes(): array
    {
        return $this->prizes;
    }

    public function toPrizeMoneyCollection(int $prizeMoney): PrizeMoneyCollection
    {
        $prizeMoney = array_map(
            fn (Prize $prize) => $prize->toPrizeMoney($prizeMoney),
            $this->prizes
        );

        return new PrizeMoneyCollection(...$prizeMoney);
    }
}
