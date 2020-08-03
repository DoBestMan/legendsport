<?php

namespace App\Domain;

use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;

/**
 * TournamentPlayers
 *
 * @ORM\Table(name="tournament_players", uniqueConstraints={@ORM\UniqueConstraint(name="tournament_id_user_id", columns={"tournament_id", "user_id"})}, indexes={@ORM\Index(name="tournament_players_user_id_foreign", columns={"user_id"}), @ORM\Index(name="IDX_4D41B8AC33D1A3E7", columns={"tournament_id"})})
 * @ORM\Entity
 */
class TournamentPlayer
{
    /**
     * @ORM\Column(name="id", type="bigint", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;
    /** @ORM\Column(name="chips", type="integer", nullable=false, options={"unsigned"=true}) */
    private int $chips;
    /** @ORM\Column(name="created_at", type="datetime", nullable=true) */
    private ?\DateTime $createdAt;
    /** @ORM\Column(name="updated_at", type="datetime", nullable=true) */
    private ?\DateTime $updatedAt;
    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Tournament", inversedBy="players")
     * @ORM\JoinColumn(name="tournament_id", referencedColumnName="id")
     */
    private Tournament $tournament;
    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\User", inversedBy="tournaments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private User $user;

    public function __construct(Tournament $tournament, User $user, int $chips)
    {
        $this->chips = $chips;
        $this->tournament = $tournament;
        $this->user = $user;
        $this->createdAt = Carbon::now();
        $this->updatedAt = Carbon::now();
        $user->joinTournament($this);
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getChips(): int
    {
        return $this->chips;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function getTournament(): Tournament
    {
        return $this->tournament;
    }

    public function addChips(int $chips): void
    {
        $this->chips += $chips;
    }

    public function reduceChips(int $chips): void
    {
        if ($chips > $this->chips) {
            throw BetPlacementException::notEnoughChips();
        }

        $this->chips -= $chips;
    }
}
