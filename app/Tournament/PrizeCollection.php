<?php
namespace App\Tournament;

class PrizeCollection
{
    /** @var Prize[] */
    private array $prizes;
    private int $maxPlayers;

    public function __construct(int $maxPlayers, array $prizes)
    {
        $this->maxPlayers = $maxPlayers;
        $this->prizes = $prizes;
    }

    /**
     * @return Prize[]
     */
    public function getPrizes(): array
    {
        return $this->prizes;
    }

    public function getMaxPlayers(): int
    {
        return $this->maxPlayers;
    }
}
