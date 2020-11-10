<?php
namespace App\Betting\SportEvent;

use App\Betting\TimeStatus;
use Carbon\Carbon;

class Result
{
    private string $externalEventId;
    private TimeStatus $timeStatus;
    private ?int $home;
    private ?int $away;
    private string $provider;
    private ?Carbon $startsAt;
    private ?string $homePitcher;
    private ?string $awayPitcher;

    public function __construct(
        string $externalEventId,
        string $provider,
        TimeStatus $timeStatus,
        $startsAt,
        ?int $home,
        ?int $away,
        ?string $homePitcher = null,
        ?string $awayPitcher = null
    ) {
        $this->externalEventId = $externalEventId;
        $this->startsAt = $startsAt ? new Carbon($startsAt) : null;
        $this->timeStatus = $timeStatus;
        $this->home = $home;
        $this->away = $away;
        $this->provider = $provider;
        $this->homePitcher = $homePitcher;
        $this->awayPitcher = $awayPitcher;
    }

    public function getExternalEventId(): string
    {
        return $this->externalEventId;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function getHome(): ?int
    {
        return $this->home;
    }

    public function getAway(): ?int
    {
        return $this->away;
    }

    public function getTimeStatus(): TimeStatus
    {
        return $this->timeStatus;
    }

    public function getStartsAt(): ?Carbon
    {
        return $this->startsAt;
    }

    public function getHomePitcher(): ?string
    {
        return $this->homePitcher;
    }

    public function getAwayPitcher(): ?string
    {
        return $this->awayPitcher;
    }
}
