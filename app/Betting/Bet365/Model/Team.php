<?php

namespace App\Betting\Bet365\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="bet365_team")
 */
class Team
{
    /** @ORM\Column(type="string") @ORM\Id() */
    private string $id;
    /** @ORM\Column(type="string") */
    private string $name;

    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
