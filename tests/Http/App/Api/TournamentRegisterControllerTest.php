<?php
namespace Tests\Http\App\Api;

use App\Models\Tournament;
use App\Models\User;
use App\Tournament\Enums\TournamentState;
use Illuminate\Http\Response;
use Tests\Utils\TestCase;

class TournamentRegisterControllerTest extends TestCase
{
    private Tournament $tournament;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var Tournament $tournament */
        $this->tournament = factory(Tournament::class)->create([
            "chips" => 2000,
            "commission" => 200,
            "buy_in" => 300,
        ]);
    }

    /** @test */
    public function registers_for_a_tournament()
    {
        // given
        /** @var User $user */
        $user = factory(User::class)->create([
            "balance" => 1100,
        ]);

        $this->actingAs($user);

        // when
        $response = $this->postJson(
            "http://legendsports.local/api/tournaments/{$this->tournament->id}/register",
        );

        // then
        $response->assertCreated();
        $this->tournament->refresh();
        $this->assertCount(1, $this->tournament->players);

        $player = $this->tournament->players[0];
        $this->assertSame(2000, $player->chips);
        $this->assertSame($user->id, $player->user->id);
        $this->assertSame(600, $player->user->balance);
        $this->assertCount(0, $player->bets);
    }

    /** @test */
    public function cannot_register_if_not_enough_balance()
    {
        // given
        /** @var User $user */
        $user = factory(User::class)->create([
            "balance" => 400,
        ]);

        $this->actingAs($user);

        // when
        $response = $this->postJson(
            "http://legendsports.local/api/tournaments/{$this->tournament->id}/register",
        );

        // then
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            "message" => "You don't have enough balance. Top up!",
        ]);

        $user->refresh();
        $this->tournament->refresh();
        $this->assertSame(400, $user->balance);
        $this->assertCount(0, $this->tournament->players);
    }

    /** @test */
    public function cannot_register_when_tournament_is_running()
    {
        // given
        $this->tournament->state = TournamentState::RUNNING();
        $this->tournament->save();

        /** @var User $user */
        $user = factory(User::class)->create([
            "balance" => 1100,
        ]);

        $this->actingAs($user);

        // when
        $response = $this->postJson(
            "http://legendsports.local/api/tournaments/{$this->tournament->id}/register",
        );

        // then
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson([
            "message" => "You cannot register for this tournament.",
        ]);
    }
}
