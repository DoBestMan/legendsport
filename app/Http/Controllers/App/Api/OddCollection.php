<?php
namespace App\Http\Controllers\App\Api;

use App\Betting\BettingProvider;
use App\Http\Controllers\Controller;
use App\Http\Transformers\App\ApiEventToOdds;
use App\Http\Transformers\App\SportEventOddTransformer;

class OddCollection extends Controller
{
    public function get(BettingProvider $betProvider)
    {
        return fractal()
            ->collection($betProvider->getOdds(false), new ApiEventToOdds())
            ->toArray();
    }
}
