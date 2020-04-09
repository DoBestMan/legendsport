<?php
namespace App\Http\Controllers\Backstage\Api;

use App\Betting\BettingProvider;
use App\Http\Controllers\Controller;
use App\Http\Transformers\App\SportEventTransformer;

class EventCollection extends Controller
{
    public function get(BettingProvider $eventsProvider)
    {
        return fractal()
            ->collection($eventsProvider->getEvents(), new SportEventTransformer())
            ->toArray();
    }
}
