<?php

namespace Unit\Domain;

use App\Betting\SportEventOdd;
use App\Domain\ApiEvent;
use App\Domain\BetItem;
use App\Domain\BetTypes\MoneyLineAway;
use App\Domain\Tournament;
use App\Domain\TournamentEvent;
use Decimal\Decimal;
use PHPUnit\Framework\TestCase;
use Tests\Fixture\Factory\ApiEventFactory;

/**
 * @covers App\Domain\BetItem
 * @uses App\Betting\SportEventOdd
 * @uses App\Domain\TournamentBetEvent
 * @uses App\Domain\TournamentEvent
 * @uses App\Domain\ApiEvent
 * @uses App\Domain\ApiEventOdds
 * @uses App\Domain\Tournament
 */
class BetItemTest extends TestCase
{
    public function testConstruct()
    {
        $event = new TournamentEvent(new Tournament(), new ApiEvent());
        $this->setProperty($event, 'id', 1);
        $sut = new BetItem(MoneyLineAway::class, $event);

        self::assertEquals(MoneyLineAway::class, $sut->getBetType());
        self::assertEquals($event, $sut->getEvent());
    }

    public function testCreateFromAlias()
    {
        $event = new TournamentEvent(new Tournament(), new ApiEvent());
        $this->setProperty($event, 'id', 1);
        $sut = BetItem::createFromBetTypeAlias('money_line_away', $event);

        self::assertEquals(MoneyLineAway::class, $sut->getBetType());
        self::assertEquals($event, $sut->getEvent());
    }

    public function testGetIdentifier()
    {
        $event = new TournamentEvent(new Tournament(), new ApiEvent());
        $this->setProperty($event, 'id', 1);
        $sut = new BetItem(MoneyLineAway::class, $event);

        self::assertEquals('1::App\Domain\BetTypes\MoneyLineAway', $sut->getIdentifier());
    }

    public function testGetCorrelationIdentifier()
    {
        $event = new TournamentEvent(new Tournament(), new ApiEvent());
        $this->setProperty($event, 'id', 1);
        $sut = new BetItem(MoneyLineAway::class, $event);

        self::assertEquals('1::result', $sut->getCorrelationIdentifier());
    }

    public function testMakeBetEvent()
    {
        $apiEvent = ApiEventFactory::create();

        $event = new TournamentEvent(new Tournament(), $apiEvent);
        $this->setProperty($event, 'id', 1);
        $this->setProperty($event, 'apiEvent', $apiEvent);

        $sut = new BetItem(MoneyLineAway::class, $event);

        $betEvent = $sut->makeBetEvent();
        self::assertInstanceOf(MoneyLineAway::class, $betEvent);
        self::assertEquals($event, $betEvent->getTournamentEvent());
    }

    /**
     * @param TournamentEvent $event
     * @return mixed
     */
    private function setProperty(TournamentEvent $event, $property, $value): void
    {
        (fn() => $event->{$property} = $value)->bindTo($event, get_class($event))();
    }
}
