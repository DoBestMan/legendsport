<?php
namespace Tests\Feature\Tournament;

use App\Models\ApiEvent;
use App\Models\PendingOdd;
use App\Models\Tournament;
use App\Models\TournamentEvent;
use App\Models\User;
use App\Services\TournamentPlayerService;
use App\Tournament\Exceptions\DuplicatedOddException;
use App\Tournament\ParlayBetService;
use App\Tournament\PendingOddType;
use Tests\Utils\TestCase;

class ParlayBetServiceTest extends TestCase
{
    private ParlayBetService $parlayBetService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parlayBetService = $this->app->make(ParlayBetService::class);
    }

    /**
     * @test
     */
    public function dont_allow_betting_the_same_thing_twice()
    {
        // given
        /** @var TournamentPlayerService $tournamentPlayerService */
        $tournamentPlayerService = $this->app->make(TournamentPlayerService::class);
        $apiEvent = factory(ApiEvent::class)->create([
            "api_id" => "event1",
        ]);
        $tournament = factory(Tournament::class)->create([
            "buy_in" => 100,
            "commission" => 20,
            "chips" => 500,
        ]);
        $tournamentEvent = factory(TournamentEvent::class)->create([
            "tournament_id" => $tournament->getKey(),
            "api_event_id" => $apiEvent->getKey(),
        ]);
        $user = factory(User::class)->create([
            "balance" => 1000,
        ]);
        $player = $tournamentPlayerService->register($tournament, $user);

        $this->expectException(DuplicatedOddException::class);

        // when
        $this->parlayBetService->bet(
            $tournament,
            $user,
            [
                new PendingOdd(PendingOddType::MONEY_LINE_AWAY(), $tournamentEvent, null, 140),
                new PendingOdd(PendingOddType::MONEY_LINE_AWAY(), $tournamentEvent, null, 140),
            ],
            500,
        );

        // then
        $player->refresh();
        $this->assertSame(500, $player->balance);
    }
}
