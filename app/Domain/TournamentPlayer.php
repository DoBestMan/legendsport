<?php

namespace App\Domain;

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
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="chips", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $chips;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var Tournament
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Tournament")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tournament_id", referencedColumnName="id")
     * })
     */
    private $tournament;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    public function addChips(int $chips): void
    {
        $this->chips += $chips;
    }
}
