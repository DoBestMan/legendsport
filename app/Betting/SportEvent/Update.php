<?php

namespace App\Betting\SportEvent;

class Update
{
    private string $externalId;
    private Result $result;
    private LineCollection $lines;
    private OfferCollection $offers;

    public function __construct(
        string $externalId,
        Result $result,
        LineCollection $lines,
        OfferCollection $offers
    ) {
        $this->externalId = $externalId;
        $this->result = $result;
        $this->lines = $lines;
        $this->offers = $offers;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function getResult(): Result
    {
        return $this->result;
    }

    public function getLines(): LineCollection
    {
        return $this->lines;
    }

    public function getOffers(): OfferCollection
    {
        return $this->offers;
    }
}
