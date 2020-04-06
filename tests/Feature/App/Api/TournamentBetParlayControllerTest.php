<?php
namespace Tests\Feature\App\Api;

use App\Models\Tournament;
use App\Models\TournamentEvent;
use App\Models\User;
use App\Tournament\BetStatus;
use App\Tournament\PendingOddType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\Utils\Concerns\JsonOddApiServiceConcern;
use Tests\Utils\TestCase;

class TournamentBetParlayControllerTest extends TestCase
{
    use JsonOddApiServiceConcern;

    private Tournament $tournament;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockJsonOddApiService();

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
    public function creates_parlay_bet()
    {
        // given
        /** @var User $user */
        $user = factory(User::class)->create([
            "balance" => 1100,
        ]);

        $this->actingAs($user);

        // when
        $response = $this->postJson(
            "http://legendsports.local/api/tournaments/{$this->tournament->id}/bets/parlay",
            [
                'pending_odds' => [
                    [
                        "event_id" => $this->tournament->events[0]->id,
                        "type" => "money_line_home",
                    ],
                    [
                        "event_id" => $this->tournament->events[1]->id,
                        "type" => "money_line_away",
                    ],
                ],
                'wager' => 500,
            ],
        );

        // then
        $response->assertOk();
        $this->tournament->refresh();
        $this->assertCount(1, $this->tournament->players);
        $player = $this->tournament->players[0];
        $this->assertSame(1500, $player->chips);
        $this->assertSame($user->id, $player->user->id);
        $this->assertSame(600, $player->user->balance);
        $this->assertCount(1, $player->bets);
        $bet = $player->bets[0];
        $this->assertSame(500, $bet->chips_wager);
        $this->assertSameEnum($bet->getStatus(), BetStatus::PENDING());
        $this->assertSame(1471, $bet->getChipsWin());
        $this->assertCount(2, $bet->betEvents);
        $this->assertSameEnum($bet->betEvents[0]->type, PendingOddType::MONEY_LINE_HOME());
        $this->assertSame(-140, $bet->betEvents[0]->odd);
        $this->assertSameEnum(BetStatus::PENDING(), $bet->betEvents[0]->status);
        $this->assertSameEnum(PendingOddType::MONEY_LINE_AWAY(), $bet->betEvents[1]->type);
        $this->assertSame(130, $bet->betEvents[1]->odd);
        $this->assertSameEnum(BetStatus::PENDING(), $bet->betEvents[1]->status);
    }

    /** @test */
    public function cannot_bet_if_not_enough_balance()
    {
        // given
        /** @var User $user */
        $user = factory(User::class)->create([
            "balance" => 400,
        ]);

        $this->actingAs($user);

        // when
        $response = $this->postJson(
            "http://legendsports.local/api/tournaments/{$this->tournament->id}/bets/parlay",
            [
                'pending_odds' => [
                    [
                        "event_id" => $this->tournament->events[0]->id,
                        "type" => "money_line_home",
                    ],
                    [
                        "event_id" => $this->tournament->events[1]->id,
                        "type" => "money_line_away",
                    ],
                ],
                'wager' => 500,
            ],
        );

        // then
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            "message" => "You don't have enough balance. Top up!",
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

        // when
        $response = $this->postJson(
            "http://legendsports.local/api/tournaments/{$this->tournament->id}/bets/parlay",
            [
                'pending_odds' => [
                    [
                        "event_id" => $this->tournament->events[0]->id,
                        "type" => "money_line_home",
                    ],
                    [
                        "event_id" => $this->tournament->events[1]->id,
                        "type" => "money_line_away",
                    ],
                ],
                'wager' => 2100,
            ],
        );

        // then
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            "message" => "You don't have enough chips.",
        ]);

        $user->refresh();
        $this->assertSame(1000, $user->balance);
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

        // when
        $response = $this->postJson(
            "http://legendsports.local/api/tournaments/{$this->tournament->id}/bets/parlay",
            [
                'pending_odds' => [
                    [
                        "event_id" => $this->tournament->events[0]->id,
                        "type" => "money_line_home",
                    ],
                    [
                        "event_id" => $this->tournament->events[1]->id,
                        "type" => "money_line_away",
                    ],
                ],
                'wager' => 99,
            ],
        );

        // then
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertExactJson([
            "errors" => [
                "wager" => ["The wager must be at least 100."]
            ],
            "message" => "The given data was invalid.",
        ]);
    }
}
