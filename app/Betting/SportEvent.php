<?php
namespace App\Betting;

use Carbon\Carbon;

class SportEvent
{
    private string $externalId;
    private ?Carbon $startsAt;
    private string $sportId;
    private string $homeTeam;
    private string $awayTeam;
    private ?string $provider;

    public function __construct(
        string $externalId,
        $startsAt,
        string $sportId,
        string $homeTeam,
        string $awayTeam,
        ?string $provider
    ) {
        $this->externalId = $externalId;
        $this->startsAt = $startsAt ? new Carbon($startsAt) : null;
        $this->sportId = $sportId;
        $this->homeTeam = $homeTeam;
        $this->awayTeam = $awayTeam;
        $this->provider = $provider;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function getStartsAt(): ?Carbon
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

    public function getProvider(): ?string
    {
        return $this->provider;
    }
}
