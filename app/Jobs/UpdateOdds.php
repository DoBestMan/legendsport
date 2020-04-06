<?php
namespace App\Jobs;

use App\Http\Transformers\App\EventOddTransformer;
use App\Services\OddService;
use App\Tournament\Events\OddsUpdate;
use Illuminate\Contracts\Events\Dispatcher;

class UpdateOdds
{
    public function handle(Dispatcher $dispatcher, OddService $oddService)
    {
        $eventOdds = collect($oddService->getOdds())
            ->map(fn(array $event) => $event["Odds"])
            ->all();

        $odds = fractal()
            ->collection($eventOdds, new EventOddTransformer())
            ->toArray();

        $dispatcher->dispatch(new OddsUpdate($odds));
    }
}
