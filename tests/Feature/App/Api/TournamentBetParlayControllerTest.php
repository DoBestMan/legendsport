<?php
namespace Tests\Feature\App\Api;

use App\Betting\SportEventOdd;
use App\Betting\TimeStatus;
use App\Models\Tournament;
use App\Models\TournamentEvent;
use App\Models\User;
use App\Services\TournamentPlayerService;
use App\Tournament\BetStatus;
use App\Tournament\PendingOddType;
use Illuminate\Http\Response;
use Tests\Utils\Concerns\BettingProviderConcern;
use Tests\Utils\TestCase;

class TournamentBetParlayControllerTest extends TestCase
{
    use BettingProviderConcern;

    private Tournament $tournament;
    private TournamentPlayerService $tournamentPlayerService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockBettingProvider();
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

        $this->bettingProvider
            ->shouldReceive("getOdds")
            ->andReturn([
                new SportEventOdd($events[0]->apiEvent->api_id, -140, 120),
                new SportEventOdd($events[1]->apiEvent->api_id, -150, 130),
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
        $this->tournamentPlayerService->register($this->tournament, $user);

        // when
        $response = $this->postJson(
            "http://legendsports.local/api/tournaments/{$this->tournament->id}/bets/parlay",
            [
                "pending_odds" => [
                    [
                        "event_id" => $this->tournament->events[0]->id,
                        "type" => "money_line_home",
                    ],
                    [
                        "event_id" => $this->tournament->events[1]->id,
                        "type" => "money_line_away",
                    ],
                ],
                "wager" => 500,
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
            "http://legendsports.local/api/tournaments/{$this->tournament->id}/bets/parlay",
            [
                "pending_odds" => [
                    [
                        "event_id" => $this->tournament->events[0]->id,
                        "type" => "money_line_home",
                    ],
                    [
                        "event_id" => $this->tournament->events[1]->id,
                        "type" => "money_line_away",
                    ],
                ],
                "wager" => 500,
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
            "http://legendsports.local/api/tournaments/{$this->tournament->id}/bets/parlay",
            [
                "pending_odds" => [
                    [
                        "event_id" => $this->tournament->events[0]->id,
                        "type" => "money_line_home",
                    ],
                    [
                        "event_id" => $this->tournament->events[1]->id,
                        "type" => "money_line_away",
                    ],
                ],
                "wager" => 2100,
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
            "http://legendsports.local/api/tournaments/{$this->tournament->id}/bets/parlay",
            [
                "pending_odds" => [
                    [
                        "event_id" => $this->tournament->events[0]->id,
                        "type" => "money_line_home",
                    ],
                    [
                        "event_id" => $this->tournament->events[1]->id,
                        "type" => "money_line_away",
                    ],
                ],
                "wager" => 99,
            ],
        );

        // then
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertExactJson([
            "errors" => [
                "wager" => ["The wager must be at least 100."],
            ],
            "message" => "The given data was invalid.",
        ]);

        $player->refresh();
        $this->assertCount(0, $player->bets);
    }

    /** @test */
    public function cannot_bet_if_match_has_already_begun()
    {
        // given
        /** @var User $user */
        $user = factory(User::class)->create([
            "balance" => 1000,
        ]);

        $this->actingAs($user);
        $player = $this->tournamentPlayerService->register($this->tournament, $user);

        $tournamentEvent = $this->tournament->events[0];
        $apiEvent = $tournamentEvent->apiEvent;
        $apiEvent->time_status = TimeStatus::IN_PLAY();
        $apiEvent->save();

        // when
        $response = $this->postJson(
            "http://legendsports.local/api/tournaments/{$this->tournament->id}/bets/parlay",
            [
                "pending_odds" => [
                    [
                        "event_id" => $tournamentEvent->id,
                        "type" => "money_line_home",
                    ],
                    [
                        "event_id" => $this->tournament->events[1]->id,
                        "type" => "money_line_away",
                    ],
                ],
                "wager" => 100,
            ],
        );

        // then
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            "message" => "The match has already begun.",
        ]);

        $player->refresh();
        $this->assertCount(0, $player->bets);
    }
}
