<?php

namespace App\Domain;

use Carbon\Carbon;
use Decimal\Decimal;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity() */
class ApiEventOdds
{
    /** @ORM\Column(type="integer") @ORM\Id() @ORM\GeneratedValue(strategy="IDENTITY") */
    private int $id;
    /** @ORM\Column(type="string") */
    private string $betType;
    /** @ORM\Column(type="integer") */
    private int $odds;
    /** @ORM\Column(type="DecimalObject", nullable=true) */
    private ?Decimal $handicap;
    /** @ORM\Column(name="created_at", type="datetime", nullable=true) */
    private ?\DateTime $createdAt;
    /** @ORM\Column(name="updated_at", type="datetime", nullable=true) */
    private ?\DateTime $updatedAt;
    /**
     * @ORM\ManyToOne(targetEntity="\App\Domain\ApiEvent", inversedBy="odds")
     * @ORM\JoinColumn(name="api_event_id", referencedColumnName="id")
     */
    private ?ApiEvent $event;

    public function __construct(ApiEvent $event, string $betType, int $odds, ?Decimal $handicap = null)
    {
        $this->betType = $betType;
        $this->odds = $odds;
        $this->handicap = $handicap;
        $this->event = $event;
        $this->createdAt = Carbon::now();
        $this->updatedAt = Carbon::now();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getBetType(): string
    {
        return $this->betType;
    }

    public function getOdds(): int
    {
        return $this->odds;
    }

    public function getHandicap(): ?Decimal
    {
        return $this->handicap;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function getEvent(): ApiEvent
    {
        return $this->event;
    }

    public function update(int $odds, ?Decimal $handicap = null): void
    {
        if ($this->odds !== $odds || $this->handicap !== $handicap) {
            $this->odds = $odds;
            $this->handicap = $handicap;
            $this->updatedAt = Carbon::now();
        }
    }

    public function suspended(): void
    {
        $this->event = null;
    }
}
