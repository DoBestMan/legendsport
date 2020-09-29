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
     * @ORM\Column(name="id", type="smallint", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;
    /** @ORM\Column(name="name", type="string", length=255, nullable=false) */
    private string $name;
    /** @ORM\Column(name="email", type="string", length=255, nullable=false) */
    private string $email;
    /** @ORM\Column(type="string") */
    private string $firstname;
    /** @ORM\Column(type="string") */
    private string $lastname;
    /** @ORM\Column(type="datetime") */
    private \DateTime $dateOfBirth;
    /** @ORM\Column(name="email_verified_at", type="datetime", nullable=true) */
    private ?\DateTime $emailVerifiedAt;
    /** @ORM\Column(name="password", type="string", length=255, nullable=false) */
    private string $password;
    /** @ORM\Column(name="balance", type="integer", nullable=false, options={"unsigned"=true}) */
    private int $balance = 0;
    /** @ORM\Column(name="remember_token", type="string", length=100, nullable=true) */
    private ?string $rememberToken;
    /** @ORM\Column(name="created_at", type="datetime", nullable=true) */
    private ?\DateTime $createdAt;
    /** @ORM\Column(name="updated_at", type="datetime", nullable=true) */
    private $updatedAt;
    /** @ORM\OneToMany(targetEntity="\App\Domain\TournamentPlayer", mappedBy="user") */
    private Collection $tournaments;
    /** @ORM\OneToMany(targetEntity=Withdrawal::class, mappedBy="user", cascade={"ALL"}) */
    private Collection $withdrawals;

    public function __construct(string $name, string $email, string $password, string $firstname, string $lastname, \DateTime $dateOfBirth)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->tournaments = new ArrayCollection();
        $this->withdrawals = new ArrayCollection();
        $this->createdAt = Carbon::now();
        $this->updatedAt = Carbon::now();
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->dateOfBirth = $dateOfBirth;
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

    public function makeWithdrawal(string $btcAddress, int $amount)
    {
        if ($this->balance < $amount) {
            throw UserBalanceException::insufficientBalanceToMakeWithdrawal($amount, $this->balance);
        }

        $this->withdrawals->add(new Withdrawal($this, $btcAddress, $amount));
        $this->balance -= $amount;
    }
}
