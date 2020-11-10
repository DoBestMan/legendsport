<?php

namespace Unit\BotPlayers\BettingStrategy;

use App\BotPlayers\BettingStrategy\ParlayBets;
use App\BotPlayers\BettingStrategy\WagerCalculator;
use App\Domain\Bot;
use App\Domain\Tournament;
use App\Domain\TournamentBet;
use App\Domain\TournamentPlayer;
use PHPUnit\Framework\TestCase;
use Mockery as m;
use Tests\Fixture\Factory\ApiEventFactory;
use Tests\Fixture\Factory\FactoryAbstract;

/**
 * @covers \App\BotPlayers\BettingStrategy\ParlayBets
 * @uses \App\Domain\ApiEvent
 * @uses \App\Domain\ApiEventOdds
 * @uses \App\Domain\TournamentBet
 * @uses \App\Domain\BetItem
 * @uses \App\Domain\Tournament
 * @uses \App\Domain\TournamentBetEvent
 * @uses \App\Domain\TournamentEvent
 * @uses \App\Domain\TournamentPlayer
 * @uses \App\Domain\User
 */
class ParlayBetsTest extends TestCase
{
    public function testPlaceBets()
    {
        $wagerCalculator = m::mock(WagerCalculator::class);
        $wagerCalculator->shouldReceive('calculateWagers')->andReturn([0, 15]);
        $sut = new ParlayBets($wagerCalculator, 2, 1, 1);

        $tournament = new Tournament();
        FactoryAbstract::setProperty($tournament, 'id', 1);
        FactoryAbstract::setProperty($tournament, 'chips', 1500);
        $event = ApiEventFactory::create();
        $tournament->addEvent($event);

        $event = $tournament->getEvents()->first();
        FactoryAbstract::setProperty($event, 'id', 1);

        $user = Bot::create('bot');
        $tournament->registerPlayer($user);
        $tournamentPlayer = $user->getTournamentPlayer($tournament);

        $sut->placeBets($tournament, $tournamentPlayer, 15);

        self::assertEquals(0, $tournamentPlayer->getChips());
        self::assertEquals(1, $tournament->getBets()->count());
        /** @var TournamentBet $bet */
        $bet = $tournament->getBets()->first();
        self::assertEquals(2, $bet->getEvents()->count());
    }
}
