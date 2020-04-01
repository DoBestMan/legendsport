<?php
namespace App\Tournament;

use Decimal\Decimal;

class Prize
{
    private Decimal $prize;
    private int $maxPosition;

    public function __construct(int $maxPosition, $prize)
    {
        $this->maxPosition = $maxPosition;
        $this->prize = new Decimal($prize);
    }

    public function getPrize(): Decimal
    {
        return $this->prize;
    }

    public function getMaxPosition(): int
    {
        return $this->maxPosition;
    }
}
