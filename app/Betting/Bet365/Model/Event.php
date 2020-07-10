<?php

namespace App\Betting\Bet365\Model;

use App\Betting\Bets365;
use App\Betting\SportEvent;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="bet365_event")
 */
class Event
{
    /** @ORM\Column(type="string") @ORM\Id() */
    private string $id;
    /** @ORM\Column(type="integer") */
    private int $time;
    /**
     * @ORM\ManyToOne(targetEntity="League")
     * @ORM\JoinColumn(name="league_id", referencedColumnName="id")
     */
    private League $league;
    /**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(name="home_id", referencedColumnName="id")
     */
    private Team $home;
    /**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(name="away_id", referencedColumnName="id")
     */
    private Team $away;

    public function __construct(string $id, int $time, League $league, Team $home, Team $away)
    {
        $this->id = $id;
        $this->time = $time;
        $this->league = $league;
        $this->home = $home;
        $this->away = $away;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTime(): int
    {
        return $this->time;
    }

    public function getLeague(): League
    {
        return $this->league;
    }

    public function getHome(): Team
    {
        return $this->home;
    }

    public function getAway(): Team
    {
        return $this->away;
    }

    public function toSportEvent(): SportEvent
    {
        return new SportEvent(
            $this->id,
            $this->time,
            $this->league->getSport()->getId(),
            $this->home->getName(),
            $this->away->getName(),
            Bets365::PROVIDER_NAME
        );
    }
}
