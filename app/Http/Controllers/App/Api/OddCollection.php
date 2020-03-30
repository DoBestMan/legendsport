<?php
namespace App\Http\Controllers\App\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\App\EventOddTransformer;
use App\Services\OddService;

class OddCollection extends Controller
{
    public function get(OddService $oddService)
    {
        $eventOdds = collect($oddService->getOdds())
            ->map(fn(array $event) => $event["Odds"])
            ->all();

        return fractal()
            ->collection($eventOdds)
            ->transformWith(new EventOddTransformer())
            ->toArray();
    }
}
