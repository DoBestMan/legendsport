<?php

namespace App\Domain;

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
}
