<?php
namespace App\SportEvent;

class SportEventOdd
{
    private string $externalEventId;

    private int $moneyLineAway;
    private int $moneyLineHome;

    private int $pointSpreadHome;
    private int $pointSpreadAway;
    private int $pointSpreadHomeLine;
    private int $pointSpreadAwayLine;

    private int $overLine;
    private int $underLine;
    private int $totalNumber;

    public function __construct(
        string $externalEventId,
        int $moneyLineHome,
        int $moneyLineAway,
        int $pointSpreadHome,
        int $pointSpreadAway,
        int $pointSpreadHomeLine,
        int $pointSpreadAwayLine,
        int $overLine,
        int $underLine,
        int $totalNumber
    ) {
        $this->externalEventId = $externalEventId;
        $this->moneyLineHome = $moneyLineHome;
        $this->moneyLineAway = $moneyLineAway;
        $this->pointSpreadHome = $pointSpreadHome;
        $this->pointSpreadAway = $pointSpreadAway;
        $this->pointSpreadHomeLine = $pointSpreadHomeLine;
        $this->pointSpreadAwayLine = $pointSpreadAwayLine;
        $this->overLine = $overLine;
        $this->underLine = $underLine;
        $this->totalNumber = $totalNumber;
    }

    public function getExternalEventId(): string
    {
        return $this->externalEventId;
    }

    public function getMoneyLineAway(): int
    {
        return $this->moneyLineAway;
    }

    public function getMoneyLineHome(): int
    {
        return $this->moneyLineHome;
    }

    public function getPointSpreadHome(): int
    {
        return $this->pointSpreadHome;
    }

    public function getPointSpreadAway(): int
    {
        return $this->pointSpreadAway;
    }

    public function getPointSpreadHomeLine(): int
    {
        return $this->pointSpreadHomeLine;
    }

    public function getPointSpreadAwayLine(): int
    {
        return $this->pointSpreadAwayLine;
    }

    public function getOverLine(): int
    {
        return $this->overLine;
    }

    public function getUnderLine(): int
    {
        return $this->underLine;
    }

    public function getTotalNumber(): int
    {
        return $this->totalNumber;
    }
}
