<?php

namespace App\Betting\Bet365\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="bet365_sport")
 */
class Sport
{
    /** @ORM\Column(type="string") @ORM\Id() */
    private string $id;
    /** @ORM\Column(type="string") */
    private string $name;
    /** @ORM\Column(type="boolean") */
    private bool $enabled = true;

    public function __construct(string $id, string $name, bool $enabled = false)
    {
        $this->id = $id;
        $this->name = $name;
        $this->enabled = $enabled;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function toSport(): \App\Betting\Sport
    {
        return new \App\Betting\Sport($this->id, $this->name);
    }
}
