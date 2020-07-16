<?php

namespace App\Domain;

use App\Betting\SportEventOdd;
use App\Domain\BetTypes\MoneyLineAway;
use App\Domain\BetTypes\MoneyLineHome;
use App\Domain\BetTypes\SpreadAway;
use App\Domain\BetTypes\SpreadHome;
use App\Domain\BetTypes\TotalOver;
use App\Domain\BetTypes\TotalUnder;
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

    /**
     * @var string
     *
     * @ORM\Column(name="time_status", type="string", length=255, nullable=false)
     */
    private $timeStatus;

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

    public function getTimeStatus(): string
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

    public function isCancelled(): bool
    {
        return $this->timeStatus === 'canceled';
    }

    public function isFinished(): bool
    {
        return $this->timeStatus === 'ended' || $this->isCancelled();
    }

    //@TODO remove this method, exists to allow syncing uncommited eloquent changes while running in hybrid mode.
    public function sync(\App\Models\ApiEvent $apiEvent): void
    {
        $this->timeStatus = $apiEvent->time_status->getValue();
        $this->scoreHome = $apiEvent->score_home;
        $this->scoreAway = $apiEvent->score_away;
    }

    public function isUpcoming()
    {
        return $this->timeStatus === 'not_started';
    }

    public function updateOdds(SportEventOdd $odds): void
    {
        $moneyLineHome = $this->odds->get(MoneyLineHome::class);
        if ($moneyLineHome === null) {
            $moneyLineHome = new ApiEventOdds($this, MoneyLineHome::class, $odds->getMoneyLineHome());
            $this->odds->set(MoneyLineHome::class, $moneyLineHome);
        } else {
            $moneyLineHome->update($odds->getMoneyLineHome());
        }

        $moneyLineAway = $this->odds->get(MoneyLineAway::class);
        if ($moneyLineAway === null) {
            $moneyLineAway = new ApiEventOdds($this, MoneyLineAway::class, $odds->getMoneyLineAway());
            $this->odds->set(MoneyLineAway::class, $moneyLineAway);
        } else {
            $moneyLineHome->update($odds->getMoneyLineAway());
        }

        $spreadHome = $this->odds->get(SpreadHome::class);
        if ($spreadHome === null) {
            $spreadHome = new ApiEventOdds($this, SpreadHome::class, $odds->getPointSpreadHome(), $odds->getPointSpreadHomeLine());
            $this->odds->set(SpreadHome::class, $spreadHome);
        } else {
            $moneyLineHome->update($odds->getPointSpreadHome(), $odds->getPointSpreadHomeLine());
        }

        $spreadAway = $this->odds->get(SpreadAway::class);
        if ($spreadAway === null) {
            $spreadAway = new ApiEventOdds($this, SpreadAway::class, $odds->getPointSpreadAway(), $odds->getPointSpreadAwayLine());
            $this->odds->set(SpreadAway::class, $spreadAway);
        } else {
            $moneyLineAway->update($odds->getPointSpreadAway(), $odds->getPointSpreadAwayLine());
        }

        $totalOver = $this->odds->get(TotalOver::class);
        if ($totalOver === null) {
            $totalOver = new ApiEventOdds($this, TotalOver::class, $odds->getOverLine(), $odds->getTotalNumber());
            $this->odds->set(TotalOver::class, $totalOver);
        } else {
            $moneyLineAway->update($odds->getOverLine(), $odds->getTotalNumber());
        }

        $totalUnder = $this->odds->get(TotalUnder::class);
        if ($totalUnder === null) {
            $totalUnder = new ApiEventOdds($this, TotalUnder::class, $odds->getOverLine(), $odds->getTotalNumber());
            $this->odds->set(TotalUnder::class, $totalUnder);
        } else {
            $moneyLineAway->update($odds->getOverLine(), $odds->getTotalNumber());
        }
    }
}
