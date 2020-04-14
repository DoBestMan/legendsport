<?php
namespace Tests\Http\App\Api;

use App\Models\Tournament;
use App\Models\TournamentPlayer;
use Tests\Utils\TestCase;

class TournamentCollectionTest extends TestCase
{
    /** @test */
    public function players_are_sorted_by_balance()
    {
        // given
        /** @var Tournament $tournament */
        $tournament = factory(Tournament::class)->create();
        $adamPlayer = factory(TournamentPlayer::class)->create([
            "tournament_id" => $tournament->id,
            "chips" => 300,
        ]);
        $victoriaPlayer = factory(TournamentPlayer::class)->create([
            "tournament_id" => $tournament->id,
            "chips" => 400,
        ]);
        $stefanPlayer = factory(TournamentPlayer::class)->create([
            "tournament_id" => $tournament->id,
            "chips" => 50,
        ]);

        // when
        $response = $this->getJson("http://legendsports.local/api/tournaments");

        // then
        $response->assertOk()->assertJson([
            [
                "players" => [
                    [
                        "balance" => 400,
                        "id" => $victoriaPlayer->getKey(),
                    ],
                    [
                        "balance" => 300,
                        "id" => $adamPlayer->getKey(),
                    ],
                    [
                        "balance" => 50,
                        "id" => $stefanPlayer->getKey(),
                    ],
                ],
            ],
        ]);
    }
}
