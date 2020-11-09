<?php

namespace Unit\Http\Transformers\App;

use App\Domain\ApiEvent;
use App\Domain\BetItem;
use App\Domain\BetTypes\MoneyLineAway;
use App\Domain\Tournament;
use App\Domain\TournamentBet;
use App\Domain\TournamentEvent;
use App\Http\Transformers\App\DoctrineTournamentBetEventTransformer;
use App\Tournament\Enums\BetStatus;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Tests\Fixture\Factory\ApiEventFactory;
use Tests\Fixture\Factory\FactoryAbstract;

/**
 * @covers App\Http\Transformers\App\DoctrineTournamentBetTransformer
 * @uses App\Domain\ApiEvent
 * @uses App\Domain\BetItem
 * @uses App\Domain\Tournament
 * @uses App\Domain\TournamentBet
 * @uses App\Domain\TournamentEvent
 */
class TournamentBetEventTransformerTest extends TestCase
{
    public function testTransform()
    {
        $apiEvent = ApiEventFactory::create();
        FactoryAbstract::setProperty($apiEvent, 'teamAway', 'TeamAway');
        FactoryAbstract::setProperty($apiEvent, 'teamHome', 'TeamHome');
        FactoryAbstract::setProperty($apiEvent, 'scoreAway', 10);
        FactoryAbstract::setProperty($apiEvent, 'scoreHome', 20);
        $dateTime = new \DateTime();
        FactoryAbstract::setProperty($apiEvent, 'startsAt', $dateTime);

        $event = new TournamentEvent(new Tournament(), $apiEvent);
        FactoryAbstract::setProperty($event, 'id', 1);
        FactoryAbstract::setProperty($event, 'apiEvent', $apiEvent);
        $sut = new BetItem(MoneyLineAway::class, $event);
        $betEvent = $sut->makeBetEvent();
        FactoryAbstract::setParentProperty($betEvent, 'id', 1);
        FactoryAbstract::setProperty($betEvent, 'status', BetStatus::PENDING());

        $expected = [ 
            "id" => 1,
            "external_id" => 1,
            "odd" => -200,
            "score_away" => 10,
            "score_home" => 20,
            "selected_team" => "TeamAway",
            "starts_at" => (new Carbon($dateTime))->toAtomString(),
            "status" => BetStatus::PENDING(),
            "team_away" => 'TeamAway',
            "team_home" => 'TeamHome',
            "type" => 'money_line_away',
            "handicap" => null
        ];
        $tbet = new DoctrineTournamentBetEventTransformer();
        $result = $tbet->transform($betEvent);
        self::assertEquals($expected, $result);
    }
}
