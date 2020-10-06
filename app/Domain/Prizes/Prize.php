<?php
namespace App\Domain\Prizes;

class Prize
{
    private float $prizePercentage;
    private int $maxPosition;

    public function __construct(int $maxPosition, float $prizePercentage)
    {
        $this->maxPosition = $maxPosition;
        $this->prizePercentage = $prizePercentage;
    }

    public function getPrizePercentage(): float
    {
        return $this->prizePercentage;
    }

    public function asDecimal(): float
    {
        return $this->prizePercentage / 100;
    }

    public function toPrizeMoney(int $totalPrizePool): PrizeMoney
    {
        return new PrizeMoney($this->maxPosition, $this->asDecimal() * $totalPrizePool);
    }

    public function getMaxPosition(): int
    {
        return $this->maxPosition;
    }
}
