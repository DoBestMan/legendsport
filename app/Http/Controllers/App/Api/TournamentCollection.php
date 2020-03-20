<?php
namespace App\Http\Controllers\App\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\App\TournamentTranformer;
use App\Models\Tournament;

class TournamentCollection extends Controller
{
    public function get()
    {
        $tournaments = Tournament::with(['events', 'events.apiEvent', 'players', 'sports'])->get();

        return fractal()
            ->collection($tournaments)
            ->transformWith(new TournamentTranformer())
            ->toArray();
    }
}
