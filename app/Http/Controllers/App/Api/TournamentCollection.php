<?php
namespace App\Http\Controllers\App\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\App\TournamentTranformer;
use App\Models\Tournament;
use Illuminate\Http\Request;

class TournamentCollection extends Controller
{
    public function get(Request $request)
    {
        $tournaments = Tournament::with(['events', 'events.apiEvent', 'players', 'players.user'])->get();

        return fractal()
            ->collection($tournaments, new TournamentTranformer($request->user()))
            ->toArray();
    }
}
