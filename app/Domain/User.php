<?php

namespace App\Domain;

use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Users
 *
 * @ORM\Table(name="users", uniqueConstraints={@ORM\UniqueConstraint(name="users_email_unique", columns={"email"}), @ORM\UniqueConstraint(name="users_name_unique", columns={"name"})})
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="is_bot", type="integer")
 * @ORM\DiscriminatorMap({
 *     1: "App\Domain\Bot",
 *     0: "App\Domain\User",
 * })
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="smallint", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="email_verified_at", type="datetime", nullable=true)
     */
    private $emailVerifiedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var int
     *
     * @ORM\Column(name="balance", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $balance = 0;

    /**
     * @var string|null
     *
     * @ORM\Column(name="remember_token", type="string", length=100, nullable=true)
     */
    private $rememberToken;

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
     * @ORM\OneToMany(targetEntity="\App\Domain\TournamentPlayer", mappedBy="user")
     */
    private Collection $tournaments;

    public function __construct(string $name, string $email, string $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->tournaments = new ArrayCollection();
        $this->createdAt = Carbon::now();
        $this->updatedAt = Carbon::now();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getEmailVerifiedAt(): ?\DateTime
    {
        return $this->emailVerifiedAt;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function getRememberToken(): ?string
    {
        return $this->rememberToken;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function getTournaments()
    {
        return $this->tournaments;
    }

    public function getTournamentPlayer(Tournament $tournament): ?TournamentPlayer
    {
        return $this->tournaments->filter(function (TournamentPlayer $tournamentPlayer) use ($tournament) {
            return $tournamentPlayer->getTournament()->getId() === $tournament->getId();
        })->first() ?: null;
    }

    public function joinTournament(TournamentPlayer $tournament): void
    {
        $this->tournaments->add($tournament);
    }
}
