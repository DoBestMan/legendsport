<?php
namespace App\Http\Controllers\App\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\App\TournamentTransformer;
use App\Models\Tournament;

class TournamentCollection extends Controller
{
    public function get()
    {
        $tournaments = Tournament::with([
            'events',
            'events.apiEvent',
            'players',
            'players.user',
        ])->get();

        return fractal()
            ->collection($tournaments, new TournamentTransformer())
            ->toArray();
    }
}
