<?php

namespace App\Betting\Bet365\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="bet365_league")
 */
class League
{
    /** @ORM\Column(type="string") @ORM\Id() */
    private string $id;
    /** @ORM\Column(type="string") */
    private string $name;
    /**
     * @ORM\ManyToOne(targetEntity="App\Betting\Bet365\Model\Sport")
     * @ORM\JoinColumn(name="sport_id", referencedColumnName="id")
     */
    private Sport $sport;
    /** @ORM\Column(type="boolean") */
    private bool $enabled = false;

    public function __construct(string $id, string $name, Sport $sport)
    {
        $this->id = $id;
        $this->name = $name;
        $this->sport = $sport;

        //@TODO remove this when league admin panel is built.
        if (in_array($name, ['NHL', 'NFL', 'NBA', 'MLB'])) {
            $this->enabled = true;
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSport(): Sport
    {
        return $this->sport;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
