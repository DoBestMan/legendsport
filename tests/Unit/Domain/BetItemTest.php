<?php

namespace Unit\Domain;

use App\Domain\ApiEvent;
use App\Domain\BetItem;
use App\Domain\Tournament;
use App\Domain\TournamentBetEvent;
use App\Domain\TournamentEvent;
use App\Domain\TournamentPlayer;
use App\Domain\User;
use PHPUnit\Framework\TestCase;
use Tests\Fixture\Factory\ApiEventFactory;

/**
 * @covers \App\Domain\BetItem
 * @uses \App\Domain\TournamentBetEvent
 * @uses \App\Domain\TournamentEvent
 * @uses \App\Domain\ApiEvent
 * @uses \App\Domain\ApiEventOdds
 * @uses \App\Domain\Tournament
 */
class BetItemTest extends TestCase
{
    public function testConstruct()
    {
        $event = new TournamentEvent(new Tournament(), new ApiEvent());
        $this->setProperty($event, 'id', 1);
        $sut = new BetItem('moneyline_away', $event);

        self::assertEquals('moneyline_away', $sut->getBetType());
        self::assertEquals($event, $sut->getEvent());
    }

    public function testGetIdentifier()
    {
        $event = new TournamentEvent(new Tournament(), new ApiEvent());
        $this->setProperty($event, 'id', 1);
        $sut = new BetItem('moneyline_away', $event);

        self::assertEquals('1::moneyline_away', $sut->getIdentifier());
    }

    public function testGetCorrelationIdentifier()
    {
        $event = new TournamentEvent(new Tournament(), new ApiEvent());
        $this->setProperty($event, 'id', 1);
        $sut = new BetItem('moneyline_away', $event);

        self::assertEquals('1::result', $sut->getCorrelationIdentifier());
    }

    public function testMakeBetEvent()
    {
        $apiEvent = ApiEventFactory::create();

        $user = new User('','','','','', new \DateTime());
        $tournament = new Tournament();
        $tournamentPlayer = new TournamentPlayer($tournament, $user, 10000);

        $event = new TournamentEvent($tournament, $apiEvent);
        $this->setProperty($event, 'id', 1);
        $this->setProperty($event, 'apiEvent', $apiEvent);

        $sut = new BetItem('moneyline_away', $event);

        $betEvent = $sut->makeBetEvent($tournamentPlayer);
        self::assertInstanceOf(TournamentBetEvent::class, $betEvent);
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
