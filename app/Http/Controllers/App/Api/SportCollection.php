<?php
namespace App\Http\Controllers\App\Api;

use App\Betting\BettingProvider;
use App\Http\Controllers\Controller;
use App\Http\Transformers\App\SportTransformer;

class SportCollection extends Controller
{
    public function get(BettingProvider $bettingProvider)
    {
        return fractal()
            ->collection($bettingProvider->getSports(), new SportTransformer())
            ->toArray();
    }
}
