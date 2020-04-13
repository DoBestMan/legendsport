<?php
namespace App\Betting;

class SportEventResult
{
    private string $externalEventId;
    private int $home;
    private int $away;
    private TimeStatus $timeStatus;

    public function __construct(
        string $externalEventId,
        int $home,
        int $away,
        TimeStatus $timeStatus
    ) {
        $this->externalEventId = $externalEventId;
        $this->home = $home;
        $this->away = $away;
        $this->timeStatus = $timeStatus;
    }

    public function getExternalEventId(): string
    {
        return $this->externalEventId;
    }

    public function getHome(): int
    {
        return $this->home;
    }

    public function getAway(): int
    {
        return $this->away;
    }

    public function getTimeStatus(): TimeStatus
    {
        return $this->timeStatus;
    }
}
