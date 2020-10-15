<?php
namespace App\Domain\Prizes;

class PrizeMoney
{
    private int $prize;
    private int $maxPosition;

    public function __construct(int $maxPosition, int $prize)
    {
        $this->maxPosition = $maxPosition;
        $this->prize = $prize;
    }

    public function getPrizeMoney(): int
    {
        return $this->prize;
    }

    public function getMaxPosition(): int
    {
        return $this->maxPosition;
    }
}
