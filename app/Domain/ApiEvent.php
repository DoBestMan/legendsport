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
     * @ORM\Column(name="provider", type="string", length=255, nullable=false)
     */
    private $provider;
    /** @ORM\OneToMany(targetEntity="\App\Domain\ApiEventOdds", mappedBy="event", indexBy="betType", cascade={"ALL"}) */
    private Collection $odds;
    /**
     * @ORM\OneToMany(targetEntity="\App\Domain\TournamentEvent", mappedBy="apiEvent")
     */
    private Collection $tournamentEvents;

    public function __construct()
    {
        $this->odds = new ArrayCollection();
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

    public function isFresherThan(int $seconds): bool
    {
        $updatedAt = \DateTimeImmutable::createFromMutable($this->updatedAt);
        return $updatedAt->add(new \DateInterval('PT' . $seconds . 'S')) > new \DateTimeImmutable();
    }

    public function result(SportEventResult $sportEventResult): bool
    {
        if (
            $this->timeStatus->equals($sportEventResult->getTimeStatus()) &&
            $this->scoreHome === $sportEventResult->getHome() &&
            $this->scoreAway === $sportEventResult->getAway()
        ) {
            return false;
        }

        $this->timeStatus = $sportEventResult->getTimeStatus();
        $this->scoreHome = $sportEventResult->getHome();
        $this->scoreAway = $sportEventResult->getAway();
        $this->updatedAt = Carbon::now();

        return true;
    }

    public function isUpcoming()
    {
        return $this->timeStatus->equals(TimeStatus::NOT_STARTED());
    }

    public function updateOdds(SportEventOdd $odds): void
    {
        $this->updatedAt = Carbon::now();
        if ($odds->getMoneyLineHome() !== null) {
            $moneyLineHome = $this->odds->get(MoneyLineHome::class);
            if ($moneyLineHome === null) {
                $moneyLineHome = new ApiEventOdds($this, MoneyLineHome::class, $odds->getMoneyLineHome());
                $this->odds->set(MoneyLineHome::class, $moneyLineHome);
            } else {
                $moneyLineHome->update($odds->getMoneyLineHome());
            }
        }

        if ($odds->getMoneyLineAway() !== null) {
            $moneyLineAway = $this->odds->get(MoneyLineAway::class);
            if ($moneyLineAway === null) {
                $moneyLineAway = new ApiEventOdds($this, MoneyLineAway::class, $odds->getMoneyLineAway());
                $this->odds->set(MoneyLineAway::class, $moneyLineAway);
            } else {
                $moneyLineAway->update($odds->getMoneyLineAway());
            }
        }

        if ($odds->getPointSpreadHome() !== null) {
            $spreadHome = $this->odds->get(SpreadHome::class);
            if ($spreadHome === null) {
                $spreadHome = new ApiEventOdds($this, SpreadHome::class, $odds->getPointSpreadHome(), $odds->getPointSpreadHomeLine());
                $this->odds->set(SpreadHome::class, $spreadHome);
            } else {
                $spreadHome->update($odds->getPointSpreadHome(), $odds->getPointSpreadHomeLine());
            }
        }

        if ($odds->getPointSpreadAway() !== null) {
            $spreadAway = $this->odds->get(SpreadAway::class);
            if ($spreadAway === null) {
                $spreadAway = new ApiEventOdds($this, SpreadAway::class, $odds->getPointSpreadAway(), $odds->getPointSpreadAwayLine());
                $this->odds->set(SpreadAway::class, $spreadAway);
            } else {
                $spreadAway->update($odds->getPointSpreadAway(), $odds->getPointSpreadAwayLine());
            }
        }

        if ($odds->getOverLine() !== null) {
            $totalOver = $this->odds->get(TotalOver::class);
            if ($totalOver === null) {
                $totalOver = new ApiEventOdds($this, TotalOver::class, $odds->getOverLine(), $odds->getTotalNumber());
                $this->odds->set(TotalOver::class, $totalOver);
            } else {
                $totalOver->update($odds->getOverLine(), $odds->getTotalNumber());
            }
        }

        if ($odds->getUnderLine() !== null) {
            $totalUnder = $this->odds->get(TotalUnder::class);
            if ($totalUnder === null) {
                $totalUnder = new ApiEventOdds($this, TotalUnder::class, $odds->getUnderLine(), $odds->getTotalNumber());
                $this->odds->set(TotalUnder::class, $totalUnder);
            } else {
                $totalUnder->update($odds->getUnderLine(), $odds->getTotalNumber());
            }
        }
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
}
