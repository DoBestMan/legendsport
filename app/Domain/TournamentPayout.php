<?php

namespace App\Domain;

use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity() */
class TournamentPayout
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;
    /**
     * @ORM\ManyToOne(targetEntity=Tournament::class)
     * @ORM\JoinColumn(name="t_id", referencedColumnName="id")
     */
    private Tournament $tournament;
    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(name="u_id", referencedColumnName="id")
     */
    private User $user;
    /** @ORM\Column(type="integer") */
    private int $payout;
    /** @ORM\Column(type="datetime") */
    private \DateTime $paidDate;
    /** @ORM\Column(type="boolean") */
    private bool $isBot;
    /** @ORM\Column(type="integer") */
    private int $userId;
    /** @ORM\Column(type="integer") */
    private int $tournamentId;
    /** @ORM\Column(name="`rank`", type="integer") */
    private int $rank;

    public function __construct(Tournament $tournament, User $user, int $rank, int $payout)
    {
        $this->tournament = $tournament;
        $this->user = $user;
        $this->payout = $payout;
        $this->paidDate = Carbon::now();
        $this->isBot = $user instanceof Bot;
        $this->userId = $user->getId();
        $this->tournamentId = $tournament->getId();
        $this->rank = $rank;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTournament(): Tournament
    {
        return $this->tournament;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getPayout(): int
    {
        return $this->payout;
    }

    public function getPaidDate()
    {
        return $this->paidDate;
    }

    public function isBot(): bool
    {
        return $this->isBot;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getTournamentId(): int
    {
        return $this->tournamentId;
    }

    public function getRank(): int
    {
        return $this->rank;
    }
}
