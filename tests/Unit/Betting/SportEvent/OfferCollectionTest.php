<?php

namespace Unit\Betting\SportEvent;

use App\Betting\SportEvent\Offer;
use App\Betting\SportEvent\OfferCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Betting\SportEvent\OfferCollection
 */
class OfferCollectionTest extends TestCase
{
    public function testConstruct()
    {
        $offer = new Offer('ml::h::ft', Offer::MONEYLINE, Offer::HOME, Offer::FULL_TIME);
        $sut = new OfferCollection($offer);

        self::assertCount(1, $sut->getOffers());
        self::assertContains($offer, $sut->getOffers());
    }

    public function testConstructEmpty()
    {
        $sut = new OfferCollection();

        self::assertEmpty($sut->getOffers());
    }
}
