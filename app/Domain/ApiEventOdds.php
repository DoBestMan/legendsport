<?php

namespace App\Domain;

use App\Betting\Settlement;
use Carbon\Carbon;
use Decimal\Decimal;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity() */
class ApiEventOdds
{
    /** @ORM\Column(type="integer") @ORM\Id() @ORM\GeneratedValue(strategy="IDENTITY") */
    private int $id;
    /** @ORM\Column(type="string") */
    private string $externalId;
    /** @ORM\Column(type="string") */
    private string $betType;
    /** @ORM\Column(type="integer", nullable=true) */
    private ?int $odds;
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
    /** @ORM\Column(type=Settlement::class, nullable=true) */
    private ?Settlement $settlement = null;
    private bool $justSettled = false;

    public function __construct(ApiEvent $event, string $externalId, string $betType, ?int $odds, ?Decimal $handicap = null)
    {
        $this->betType = $betType;
        $this->odds = $odds;
        $this->handicap = $handicap;
        $this->event = $event;
        $this->createdAt = Carbon::now();
        $this->updatedAt = Carbon::now();
        $this->externalId = $externalId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
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

    public function getSettlement(): ?Settlement
    {
        return $this->settlement;
    }

    public function handleSettlement(): bool
    {
        $shouldHandleSettlement = $this->justSettled;
        $this->justSettled = false;

        return $shouldHandleSettlement;
    }

    public function update(?int $odds, ?Settlement $settlement = null): bool
    {
        $updated = false;
        if ($this->odds !== $odds) {
            $this->odds = $odds;
            $updated = true;
        }

        if ($this->settlement === null && $settlement !== null) {
            $updated = true;
            $this->settlement = $settlement;
            $this->justSettled = true;
        }

        if ($updated) {
            $this->updatedAt = Carbon::now();
        }

        return $updated;
    }

    public function suspended(): void
    {
        $this->event = null;
    }
}
