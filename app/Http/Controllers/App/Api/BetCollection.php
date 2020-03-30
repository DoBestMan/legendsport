<?php
namespace App\Http\Controllers\App\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\App\TournamentBetTransformer;
use App\Models\User;
use Illuminate\Http\Request;

class BetCollection extends Controller
{
    public function get(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $user->load(["bets.betEvents.tournamentEvent.apiEvent"]);

        // TODO Do not return very old bets

        return fractal()
            ->collection($user->bets, new TournamentBetTransformer())
            ->toArray();
    }
}
