<?php
namespace App\Betting;

use Decimal\Decimal;

class SportEventOdd
{
    private string $externalEventId;

    /** American odds */
    private ?int $moneyLineAway;
    /** American odds */
    private ?int $moneyLineHome;

    /** American odds */
    private ?int $pointSpreadHome;
    /** American odds */
    private ?int $pointSpreadAway;
    private ?Decimal $pointSpreadHomeLine;
    private ?Decimal $pointSpreadAwayLine;

    /** American odds */
    private ?int $overLine;
    /** American odds */
    private ?int $underLine;
    private ?Decimal $totalNumber;

    public function __construct(
        string $externalEventId,
        ?int $moneyLineHome,
        ?int $moneyLineAway,
        ?int $pointSpreadHome,
        ?int $pointSpreadAway,
        ?Decimal $pointSpreadHomeLine,
        ?Decimal $pointSpreadAwayLine,
        ?int $overLine,
        ?int $underLine,
        ?Decimal $totalNumber
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

    public function getMoneyLineAway(): ?int
    {
        return $this->moneyLineAway;
    }

    public function getMoneyLineHome(): ?int
    {
        return $this->moneyLineHome;
    }

    public function getPointSpreadHome(): ?int
    {
        return $this->pointSpreadHome;
    }

    public function getPointSpreadAway(): ?int
    {
        return $this->pointSpreadAway;
    }

    public function getPointSpreadHomeLine(): ?Decimal
    {
        return $this->pointSpreadHomeLine;
    }

    public function getPointSpreadAwayLine(): ?Decimal
    {
        return $this->pointSpreadAwayLine;
    }

    public function getOverLine(): ?int
    {
        return $this->overLine;
    }

    public function getUnderLine(): ?int
    {
        return $this->underLine;
    }

    public function getTotalNumber(): ?Decimal
    {
        return $this->totalNumber;
    }
}
