<?php
namespace App\Betting;

use Carbon\Carbon;

class SportEvent
{
    private ?int $id;
    private string $externalId;
    private Carbon $startsAt;
    private string $sportId;
    private string $homeTeam;
    private string $awayTeam;

    public function __construct(
        ?int $id,
        string $externalId,
        Carbon $startsAt,
        string $sportId,
        string $homeTeam,
        string $awayTeam
    ) {
        $this->id = $id;
        $this->externalId = $externalId;
        $this->startsAt = $startsAt;
        $this->sportId = $sportId;
        $this->homeTeam = $homeTeam;
        $this->awayTeam = $awayTeam;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function getStartsAt(): Carbon
    {
        return $this->startsAt;
    }

    public function getSportId(): string
    {
        return $this->sportId;
    }

    public function getHomeTeam(): string
    {
        return $this->homeTeam;
    }

    public function getAwayTeam(): string
    {
        return $this->awayTeam;
    }
}
