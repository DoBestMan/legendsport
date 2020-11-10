<?php

namespace App\Betting\SportEvent;

class OfferCollection
{
    private array $offers;

    public function __construct(Offer ...$offers)
    {
        $this->offers = $offers;
    }

    public function getOffers(): array
    {
        return $this->offers;
    }
}
