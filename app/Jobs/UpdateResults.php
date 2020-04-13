<?php
namespace App\Jobs;

use App\Betting\BettingProvider;
use App\Http\Transformers\App\SportEventResultTransformer;
use App\Tournament\Events\ResultsUpdate;
use Illuminate\Contracts\Events\Dispatcher;

class UpdateResults
{
    public function handle(Dispatcher $dispatcher, BettingProvider $betProvider)
    {
        $odds = fractal()
            ->collection($betProvider->getResults(), new SportEventResultTransformer())
            ->toArray();

        $dispatcher->dispatch(new ResultsUpdate($odds));
    }
}
