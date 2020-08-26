<?php
namespace App\Betting;

class SportEventResult
{
    private string $externalEventId;
    private TimeStatus $timeStatus;
    private ?int $home;
    private ?int $away;
    private string $provider;

    public function __construct(
        string $externalEventId,
        string $provider,
        TimeStatus $timeStatus,
        ?int $home,
        ?int $away
    ) {
        $this->externalEventId = $externalEventId;
        $this->timeStatus = $timeStatus;
        $this->home = $home;
        $this->away = $away;
        $this->provider = $provider;
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
}
