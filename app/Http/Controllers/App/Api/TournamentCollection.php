<?php
namespace App\Http\Controllers\App\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\App\TournamentTranformer;
use App\Models\Tournament;
use App\Models\TournamentPlayer;
use Illuminate\Http\Request;

class TournamentCollection extends Controller
{
    public function get(Request $request)
    {
        $tournaments = Tournament::with([
            'events',
            'events.apiEvent',
            'players',
            'players.user',
        ])->get();

        $tournamentPlayers = TournamentPlayer::where("user_id", $request->user()->id ?? null)
            ->whereIn(
                "tournament_id",
                $tournaments->map(fn(Tournament $tournament) => $tournament->id)
            )
            ->get()
            ->mapWithKeys(fn(TournamentPlayer $player) => [$player->tournament_id => $player])
            ->all();

        return fractal()
            ->collection($tournaments, new TournamentTranformer($tournamentPlayers))
            ->toArray();
    }
}
