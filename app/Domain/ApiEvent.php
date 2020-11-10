<?php

namespace App\Domain;

use App\Betting\Settlement;
use App\Betting\SportEvent\Line;
use App\Betting\SportEvent\Offer;
use App\Betting\SportEvent\Update;
use App\Betting\SportEvent\Result;
use App\Betting\TimeStatus;
use App\Domain\ApiEvent\UpdateResult;
use Carbon\Carbon;
use Decimal\Decimal;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ApiEvents
 *
 * @ORM\Table(name="api_events")
 * @ORM\Entity
 */
class ApiEvent
{
    /**
     * @ORM\Column(name="id", type="bigint", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;
    /** @ORM\Column(name="api_id", type="string", length=255, nullable=false) */
    private string $apiId;
    /** @ORM\Column(name="created_at", type="datetime", nullable=true) */
    private ?\DateTime $createdAt;
    /** @ORM\Column(name="updated_at", type="datetime", nullable=true) */
    private ?\DateTime $updatedAt;
    /** @ORM\Column(name="sport_id", type="integer", nullable=true, options={"unsigned"=true}) */
    private ?int $sportId;
    /** @ORM\Column(name="time_status", type=TimeStatus::class, length=255, nullable=false) */
    private TimeStatus $timeStatus;
    /** @ORM\Column(name="starts_at", type="datetime", nullable=true) */
    private ?\DateTime $startsAt = null;
    /** @ORM\Column(name="team_away", type="string", length=255, nullable=false) */
    private string $teamAway;
    /** @ORM\Column(name="team_home", type="string", length=255, nullable=false) */
    private string $teamHome;
    /** @ORM\Column(name="score_away", type="integer", nullable=true, options={"unsigned"=true}) */
    private ?int $scoreAway = null;
    /** @ORM\Column(name="score_home", type="integer", nullable=true, options={"unsigned"=true}) */
    private ?int $scoreHome = null;
    /** @ORM\Column(name="pitcher_home", type="string", length=255, nullable=true) */
    private ?string $pitcherHome = null;
     /** @ORM\Column(name="pitcher_away", type="string", length=255, nullable=true) */
    private ?string $pitcherAway = null;
    /** @ORM\Column(name="provider", type="string", length=255, nullable=false) */
    private string $provider;
    /** @ORM\OneToMany(targetEntity="\App\Domain\ApiEventOdds", mappedBy="event", cascade={"ALL"}, orphanRemoval=true) */
    private Collection $odds;
    /** @ORM\Column(name="has_bettable_lines", type="boolean", nullable=false) */
    public bool $hasBettableLines = false;
    /** @ORM\OneToMany(targetEntity="\App\Domain\TournamentEvent", mappedBy="apiEvent") */
    private Collection $tournamentEvents;
    /** @ORM\Column(type="json_array") */
    private array $offers = [];

    private ?Collection $oddsByType = null;
    private ?Collection $linesByExternalId = null;
    private bool $hasLinesThatJustSettled = false;

    public function __construct()
    {
        $this->odds = new ArrayCollection();
        $this->timeStatus = TimeStatus::NOT_STARTED();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getApiId(): string
    {
        return $this->apiId;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function getSportId(): ?int
    {
        return $this->sportId;
    }

    public function getTimeStatus(): TimeStatus
    {
        return $this->timeStatus;
    }

    public function getStartsAt(): ?\DateTime
    {
        return $this->startsAt;
    }

    public function getTeamAway(): string
    {
        return $this->teamAway;
    }

    public function getTeamHome(): string
    {
        return $this->teamHome;
    }

    public function getPitcherAway(): ?string
    {
        return $this->pitcherAway;
    }

    public function getPitcherHome(): ?string
    {
        return $this->pitcherHome;
    }

    public function getScoreAway(): ?int
    {
        return $this->scoreAway;
    }

    public function getScoreHome(): ?int
    {
        return $this->scoreHome;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function isCancelled(): bool
    {
        return $this->timeStatus->equals(TimeStatus::CANCELED());
    }

    public function isFinished(): bool
    {
        return $this->timeStatus->equals(TimeStatus::ENDED()) || $this->isCancelled();
    }

    public function result(Result $sportEventResult): bool
    {
        if (
            $this->timeStatus->equals($sportEventResult->getTimeStatus()) &&
            $this->scoreHome === $sportEventResult->getHome() &&
            $this->scoreAway === $sportEventResult->getAway() &&
            $this->pitcherHome === $sportEventResult->getHomePitcher() &&
            $this->pitcherAway === $sportEventResult->getAwayPitcher() &&
            !$this->hasStartTimeChanged($sportEventResult->getStartsAt())
        ) {
            return false;
        }

        $this->timeStatus = $sportEventResult->getTimeStatus();
        $this->scoreHome = $sportEventResult->getHome();
        $this->scoreAway = $sportEventResult->getAway();
        $this->pitcherHome = $sportEventResult->getHomePitcher();
        $this->pitcherAway = $sportEventResult->getAwayPitcher();
        $this->startsAt = $sportEventResult->getStartsAt();
        $this->updatedAt = Carbon::now();

        return true;
    }

    public function isBettable(bool $allowLiveBetting): bool
    {
        return $this->hasBettableLines &&
            ($this->timeStatus->equals(TimeStatus::NOT_STARTED()) ||
                ($this->timeStatus->equals(TimeStatus::IN_PLAY()) && $allowLiveBetting)
            );
    }

    public function getOddTypes(): array
    {
        return array_keys($this->getAllOdds()->toArray());
    }

    public function getOdds(string $betType): ?ApiEventOdds
    {
        return $this->getAllOdds()->get($betType);
    }

    public function getAllOdds(): Collection
    {
        if ($this->oddsByType === null) {
            $odds = new ArrayCollection();
            foreach ($this->offers as $lineName => $lineId) {
                if ($lineId !== null) {
                    /** @var ApiEventOdds $odd */
                    $odds->set($lineName, $this->getLine($lineId));
                }
            }

            $this->oddsByType = $odds;
        }

        return $this->oddsByType;
    }

    public function update(Update $update): UpdateResult
    {
        $linesUpdated = $this->updateLines(...$update->getLines()->getLines());
        $offersUpdated = $this->updateOffers(...$update->getOffers()->getOffers());
        $fixtureUpdated = $this->result($update->getResult());

        $hasLinesToGrade = $this->hasLinesThatJustSettled;
        $this->hasLinesThatJustSettled = false;

        return new UpdateResult($linesUpdated, $offersUpdated, $fixtureUpdated, $hasLinesToGrade);
    }

    private function updateLine(string $lineId, ?int $price, ?Decimal $line, ?Settlement $settlement): bool
    {
        $lineInstance = $this->getLine($lineId);
        if ($price === null && $lineInstance === null) {
            return false;
        }

        if ($lineInstance === null) {
            $lineInstance = new ApiEventOdds($this, $lineId, '', $price, $line);
            $this->odds->add($lineInstance);
            $this->linesByExternalId = null;
            return true;
        }

        $updated = $lineInstance->update($price, $settlement);
        $this->hasLinesThatJustSettled |= $lineInstance->handleSettlement();

        return $updated;
    }

    public function updateLines(Line ...$lines): bool
    {
        $updated = false;
        foreach ($lines as $line) {
            $updated |= $this->updateLine($line->getId(), $line->getPrice(), $line->getLine(), $line->getSettlement());
        }

        return $updated;
    }

    public function updateOffers(Offer ...$lineOffers): bool
    {
        $updated = false;
        foreach ($lineOffers as $lineOffer) {
            $lineName = $lineOffer->tagsToLineName();
            if (!array_key_exists($lineName, $this->offers) || $this->offers[$lineName] != $lineOffer->getId()) {
                $this->offers[$lineName] = $lineOffer->getId();
                $updated = true;
            }
        }

        if ($updated) {
            $this->updatedAt = Carbon::now();
            $this->hasBettableLines = count(array_filter($this->offers)) > 0;
            $this->oddsByType = null;
        }

        return $updated;
    }

    public function getLine(string $lineId): ?ApiEventOdds
    {
        return $this->getLines()->get($lineId);
    }

    public function getLines(): Collection
    {
        if ($this->linesByExternalId === null) {
            $odds = new ArrayCollection();
            foreach ($this->odds as $odd) {
                /** @var ApiEventOdds $odd */
                $odds->set($odd->getExternalId(), $odd);
            }

            $this->linesByExternalId = $odds;
        }

        return $this->linesByExternalId;
    }

    /** @return TournamentEvent[] */
    public function getTournamentEvents(): Collection
    {
        return $this->tournamentEvents;
    }

    private function hasStartTimeChanged(?\DateTime $newStartTime): bool
    {
        if ($this->startsAt === null && $newStartTime !== null) {
            return true;
        }

        if ($this->startsAt !== null && $newStartTime === null) {
            return true;
        }

        return (new Carbon($this->startsAt))->diffInSeconds($newStartTime) >= 60;
    }
}
