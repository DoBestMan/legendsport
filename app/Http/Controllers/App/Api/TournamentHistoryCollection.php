<?php
namespace App\Http\Controllers\App\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\App\TournamentTransformer;
use App\Models\Tournament;
use App\Models\TournamentPlayer;
use App\Models\User;
use App\Tournament\Enums\TournamentState;
use Illuminate\Http\Request;

class TournamentHistoryCollection extends Controller
{
    public function get(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $tournaments = collect($user->players)
            ->map(fn (TournamentPlayer $tournamentPlayer) => $tournamentPlayer->tournament)
            ->filter(fn (Tournament $tournament) => $tournament->state->equals(TournamentState::COMPLETED()));

        return fractal()
            ->collection($tournaments, new TournamentTransformer())
            ->toArray();
    }
}
