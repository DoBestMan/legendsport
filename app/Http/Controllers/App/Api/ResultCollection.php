<?php
namespace App\Http\Controllers\App\Api;

use App\Betting\BettingProvider;
use App\Http\Controllers\Controller;
use App\Http\Transformers\App\SportEventResultTransformer;

class ResultCollection extends Controller
{
    public function get(BettingProvider $betProvider)
    {
        return fractal()
            ->collection($betProvider->getResults(), new SportEventResultTransformer())
            ->toArray();
    }
}
