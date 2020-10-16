<?php

namespace App\Domain;

use App\Betting\SportEventOdd;
use App\Betting\SportEventResult;
use App\Betting\TimeStatus;
use App\Domain\BetTypes\MoneyLineAway;
use App\Domain\BetTypes\MoneyLineHome;
use App\Domain\BetTypes\SpreadAway;
use App\Domain\BetTypes\SpreadHome;
use App\Domain\BetTypes\TotalOver;
use App\Domain\BetTypes\TotalUnder;
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
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="api_id", type="string", length=255, nullable=false)
     */
    private $apiId;

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
     * @var int|null
     *
     * @ORM\Column(name="sport_id", type="integer", nullable=true, options={"unsigned"=true})
     */
    private $sportId;

    /** @ORM\Column(name="time_status", type=TimeStatus::class, length=255, nullable=false) */
    private TimeStatus $timeStatus;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="starts_at", type="datetime", nullable=true)
     */
    private $startsAt;

    /**
     * @var string
     *
     * @ORM\Column(name="team_away", type="string", length=255, nullable=false)
     */
    private $teamAway;

    /**
     * @var string
     *
     * @ORM\Column(name="team_home", type="string", length=255, nullable=false)
     */
    private $teamHome;

    /**
     * @var int|null
     *
     * @ORM\Column(name="score_away", type="integer", nullable=true, options={"unsigned"=true})
     */
    private $scoreAway;

    /**
     * @var int|null
     *
     * @ORM\Column(name="score_home", type="integer", nullable=true, options={"unsigned"=true})
     */
    private $scoreHome;

    /**
     * @var string
     *
     * @ORM\Column(name="pitcher_home", type="string", length=255, nullable=true)
     */
    private $pitcherHome;

     /**
     * @var string
     *
     * @ORM\Column(name="pitcher_away", type="string", length=255, nullable=true)
     */
    private $pitcherAway;

    /**
     * @var string
     *
     * @ORM\Column(name="provider", type="string", length=255, nullable=false)
     */
    private $provider;
    /** @ORM\OneToMany(targetEntity="\App\Domain\ApiEventOdds", mappedBy="event", indexBy="betType", cascade={"ALL"}, orphanRemoval=true) */
    private Collection $odds;
    /** @ORM\Column(name="has_bettable_lines", type="boolean", nullable=false) */
    public bool $hasBettableLines = false;
    /**
     * @ORM\OneToMany(targetEntity="\App\Domain\TournamentEvent", mappedBy="apiEvent")
     */
    private Collection $tournamentEvents;

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

    public function result(SportEventResult $sportEventResult): bool
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

    private function updateOdd(string $oddType, ?string $price, ?Decimal $line): void
    {
        $lineInstance = $this->odds->get($oddType);
        if ($price === null && $lineInstance === null) {
            return;
        }

        if ($price === null && $lineInstance !== null) {
            $lineInstance->suspended();
            $this->odds->remove($oddType);
            return;
        }

        if ($lineInstance === null) {
            $lineInstance = new ApiEventOdds($this, $oddType, $price, $line);
            $this->odds->set($oddType, $lineInstance);
            return;
        }

        $lineInstance->update($price, $line);
    }

    public function updateOdds(SportEventOdd $odds): void
    {
        $this->updatedAt = Carbon::now();
        $this->updateOdd(MoneyLineHome::class, $odds->getMoneyLineHome(), null);
        $this->updateOdd(MoneyLineAway::class, $odds->getMoneyLineAway(), null);
        $this->updateOdd(SpreadHome::class, $odds->getPointSpreadHome(), $odds->getPointSpreadHomeLine());
        $this->updateOdd(SpreadAway::class, $odds->getPointSpreadAway(), $odds->getPointSpreadAwayLine());
        $this->updateOdd(TotalOver::class, $odds->getOverLine(), $odds->getTotalNumber());
        $this->updateOdd(TotalUnder::class, $odds->getUnderLine(), $odds->getTotalNumber());

        $this->hasBettableLines = !$this->odds->isEmpty();
    }

    public function getOddTypes(): array
    {
        return array_keys($this->odds->toArray());
    }

    public function getOdds(string $betType): ?ApiEventOdds
    {
        return $this->odds->get($betType);
    }

    public function getAllOdds(): Collection
    {
        return $this->odds;
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
