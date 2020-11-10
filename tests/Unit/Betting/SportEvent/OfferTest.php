<?php

namespace Unit\Betting\SportEvent;

use App\Betting\SportEvent\Offer;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Betting\SportEvent\Offer
 */
class OfferTest extends TestCase
{
    public function testConstruct()
    {
        $sut = new Offer('ml::h::ft', Offer::MONEYLINE, Offer::HOME, Offer::FULL_TIME);

        self::assertEquals('ml::h::ft', $sut->getId());
        self::assertContains(Offer::MONEYLINE, $sut->getTags());
        self::assertContains(Offer::HOME, $sut->getTags());
        self::assertContains(Offer::FULL_TIME, $sut->getTags());
    }

    /** @dataProvider provideTagsToLineName */
    public function testTagsToLineName(array $tags, string $lineName)
    {
        $sut = new Offer('ml::h::ft', ...$tags);
        self::assertEquals($lineName, $sut->tagsToLineName());
    }

    public function provideTagsToLineName()
    {
        return [
            [[Offer::MONEYLINE, Offer::HOME, Offer::FULL_TIME], 'moneyline_home'],
            [[Offer::MONEYLINE, Offer::AWAY, Offer::FULL_TIME], 'moneyline_away'],
            [[Offer::SPREAD, Offer::HOME, Offer::FULL_TIME], 'spread_home'],
            [[Offer::SPREAD, Offer::AWAY, Offer::FULL_TIME], 'spread_away'],
            [[Offer::TOTAL, Offer::OVER, Offer::FULL_TIME], 'total_over'],
            [[Offer::TOTAL, Offer::UNDER, Offer::FULL_TIME], 'total_under'],
            [[], '']
        ];
    }
}
