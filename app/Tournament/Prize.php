<?php
namespace App\Tournament;

use Decimal\Decimal;

class Prize
{
    private float $prizePercentage;
    private int $maxPosition;

    public function __construct(int $maxPosition, $prizePercentage)
    {
        $this->maxPosition = $maxPosition;
        $this->prizePercentage = $prizePercentage;
    }

    public function getPrizePercentage(): float
    {
        return $this->prizePercentage;
    }

    public function getMaxPosition(): int
    {
        return $this->maxPosition;
    }
}
