<?php
namespace Tests\Feature\App\Api;

use App\Models\Tournament;
use App\Models\TournamentEvent;
use App\Models\User;
use App\Services\TournamentPlayerService;
use App\Tournament\BetStatus;
use App\Tournament\PendingOddType;
use Illuminate\Http\Response;
use Tests\Utils\Concerns\JsonOddApiServiceConcern;
use Tests\Utils\TestCase;

class TournamentBetStraightControllerTest extends TestCase
{
    use JsonOddApiServiceConcern;

    private Tournament $tournament;
    private TournamentPlayerService $tournamentPlayerService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockJsonOddApiService();
        $this->tournamentPlayerService = $this->app->make(TournamentPlayerService::class);

        /** @var Tournament $tournament */
        $this->tournament = factory(Tournament::class)->create([
            "chips" => 2000,
            "commission" => 200,
            "buy_in" => 300,
        ]);

        /** @var TournamentEvent[] $events */
        $events = factory(TournamentEvent::class)
            ->times(2)
            ->create([
                "tournament_id" => $this->tournament->id,
            ]);

        $this->jsonOddApiServiceMock->shouldReceive("getOdds")->andReturn([
            [
                "ID" => $events[0]->apiEvent->api_id,
                "Odds" => [
                    [
                        "ID" => "",
                        "EventID" => $events[0]->apiEvent->api_id,
                        "MoneyLineHome" => -140,
                        "MoneyLineAway" => 120,
                    ],
                ],
            ],
            [
                "ID" => $events[1]->apiEvent->api_id,
                "Odds" => [
                    [
                        "ID" => "",
                        "EventID" => $events[1]->apiEvent->api_id,
                        "MoneyLineHome" => -150,
                        "MoneyLineAway" => 130,
                    ],
                ],
            ],
        ]);
    }

    /** @test */
    public function creates_straight_bet()
    {
        // given
        /** @var User $user */
        $user = factory(User::class)->create([
            "balance" => 1100,
        ]);

        $this->actingAs($user);
        $this->tournamentPlayerService->register($this->tournament, $user);

        // when
        $response = $this->postJson(
            "http://legendsports.local/api/tournaments/{$this->tournament->id}/bets/straight",
            [
                'pending_odds' => [
                    [
                        "event_id" => $this->tournament->events[0]->id,
                        "type" => "money_line_home",
                        "wager" => 300,
                    ],
                    [
                        "event_id" => $this->tournament->events[1]->id,
                        "type" => "money_line_away",
                        "wager" => 400,
                    ],
                ],
            ],
        );

        // then
        $response->assertOk();
        $this->tournament->refresh();
        $this->assertCount(1, $this->tournament->players);
        $player = $this->tournament->players[0];
        $this->assertSame(1300, $player->chips);
        $this->assertSame($user->id, $player->user->id);
        $this->assertSame(600, $player->user->balance);
        $this->assertCount(2, $player->bets);

        $betA = $player->bets[0];
        $this->assertSame(300, $betA->chips_wager);
        $this->assertSameEnum($betA->getStatus(), BetStatus::PENDING());
        $this->assertSame(214, $betA->getChipsWin());
        $this->assertCount(1, $betA->betEvents);
        $this->assertSameEnum($betA->betEvents[0]->type, PendingOddType::MONEY_LINE_HOME());
        $this->assertSame(-140, $betA->betEvents[0]->odd);
        $this->assertSameEnum(BetStatus::PENDING(), $betA->betEvents[0]->status);

        $betB = $player->bets[1];
        $this->assertSame(400, $betB->chips_wager);
        $this->assertSameEnum($betB->getStatus(), BetStatus::PENDING());
        $this->assertSame(520, $betB->getChipsWin());
        $this->assertCount(1, $betB->betEvents);
        $this->assertSameEnum($betB->betEvents[0]->type, PendingOddType::MONEY_LINE_AWAY());
        $this->assertSame(130, $betB->betEvents[0]->odd);
        $this->assertSameEnum(BetStatus::PENDING(), $betB->betEvents[0]->status);
    }

    /** @test */
    public function cannot_bet_if_not_registered()
    {
        // given
        /** @var User $user */
        $user = factory(User::class)->create([
            "balance" => 400,
        ]);

        $this->actingAs($user);

        // when
        $response = $this->postJson(
            "http://legendsports.local/api/tournaments/{$this->tournament->id}/bets/straight",
            [
                'pending_odds' => [
                    [
                        "event_id" => $this->tournament->events[0]->id,
                        "type" => "money_line_home",
                        "wager" => 200,
                    ],
                    [
                        "event_id" => $this->tournament->events[1]->id,
                        "type" => "money_line_away",
                        "wager" => 300,
                    ],
                ],
            ],
        );

        // then
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            "message" => "You need to be registered to place a bet.",
        ]);

        $user->refresh();
        $this->assertSame(400, $user->balance);
    }

    /** @test */
    public function cannot_bet_if_not_enough_chips()
    {
        // given
        /** @var User $user */
        $user = factory(User::class)->create([
            "balance" => 1000,
        ]);

        $this->actingAs($user);
        $player = $this->tournamentPlayerService->register($this->tournament, $user);

        // when
        $response = $this->postJson(
            "http://legendsports.local/api/tournaments/{$this->tournament->id}/bets/straight",
            [
                "pending_odds" => [
                    [
                        "event_id" => $this->tournament->events[0]->id,
                        "type" => "money_line_home",
                        "wager" => 1500,
                    ],
                    [
                        "event_id" => $this->tournament->events[1]->id,
                        "type" => "money_line_away",
                        "wager" => 800,
                    ],
                ],
            ],
        );

        // then
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            "message" => "You don't have enough chips.",
        ]);

        $player->refresh();
        $this->assertCount(0, $player->bets);
    }

    /** @test */
    public function cannot_place_bet_below_minimal_wager()
    {
        // given
        /** @var User $user */
        $user = factory(User::class)->create([
            "balance" => 1000,
        ]);

        $this->actingAs($user);
        $player = $this->tournamentPlayerService->register($this->tournament, $user);

        // when
        $response = $this->postJson(
            "http://legendsports.local/api/tournaments/{$this->tournament->id}/bets/straight",
            [
                'pending_odds' => [
                    [
                        "event_id" => $this->tournament->events[0]->id,
                        "type" => "money_line_home",
                        "wager" => 99,
                    ],
                    [
                        "event_id" => $this->tournament->events[1]->id,
                        "type" => "money_line_away",
                        "wager" => 99,
                    ],
                ],
            ],
        );

        // then
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertExactJson([
            "errors" => [
                "pending_odds.0.wager" => ["The pending_odds.0.wager must be at least 100."],
                "pending_odds.1.wager" => ["The pending_odds.1.wager must be at least 100."],
            ],
            "message" => "The given data was invalid.",
        ]);

        $player->refresh();
        $this->assertCount(0, $player->bets);
    }
}
