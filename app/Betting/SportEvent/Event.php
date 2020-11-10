<?php
namespace App\Betting\SportEvent;

use Carbon\Carbon;

class Event
{
    private string $externalId;
    private ?Carbon $startsAt;
    private string $sportId;
    private string $homeTeam;
    private string $awayTeam;
    private ?string $provider;
    private ?string $homePitcher;
    private ?string $awayPitcher;

    public function __construct(
        string $externalId,
        $startsAt,
        string $sportId,
        string $homeTeam,
        string $awayTeam,
        ?string $provider,
        ?string $homePitcher,
        ?string $awayPitcher
    ) {
        $this->externalId = $externalId;
        $this->startsAt = $startsAt ? new Carbon($startsAt) : null;
        $this->sportId = $sportId;
        $this->homeTeam = $homeTeam;
        $this->awayTeam = $awayTeam;
        $this->provider = $provider;
        $this->homePitcher = $homePitcher;
        $this->awayPitcher = $awayPitcher;
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

    public function getHomePitcher(): ?string
    {
        return $this->homePitcher;
    }

    public function getAwayPitcher(): ?string
    {
        return $this->awayPitcher;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }
}
