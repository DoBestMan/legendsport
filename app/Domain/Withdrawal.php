<?php

namespace App\Domain;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity() */
class Withdrawal
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="withdrawals")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private User $user;
    /** @ORM\Column(type="string") */
    private string $btcAddress;
    /** @ORM\Column(type="integer") */
    private int $amount;
    /** @ORM\Column(type="boolean") */
    private bool $processed = false;

    public function __construct(User $user, string $btcAddress, int $amount)
    {
        $this->user = $user;
        $this->btcAddress = $btcAddress;
        $this->amount = $amount;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getBtcAddress(): string
    {
        return $this->btcAddress;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function isProcessed(): bool
    {
        return $this->processed;
    }

    public function process(): void
    {
        $this->processed = true;
    }
}
