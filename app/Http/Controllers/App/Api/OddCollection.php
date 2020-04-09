<?php
namespace App\Http\Controllers\App\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\App\EventOddTransformer;
use App\SportEvent\OddsProvider;

class OddCollection extends Controller
{
    public function get(OddsProvider $oddsProvider)
    {
        return fractal()
            ->collection($oddsProvider->getOdds(), new EventOddTransformer())
            ->toArray();
    }
}
