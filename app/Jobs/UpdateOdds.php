<?php
namespace App\Jobs;

use App\Betting\BettingProvider;
use App\Http\Transformers\App\ApiEventToOdds;
use App\Http\Transformers\App\SportEventOddTransformer;
use App\Tournament\Events\OddsUpdate;
use Illuminate\Contracts\Events\Dispatcher;

final class UpdateOdds
{
    public function handle(Dispatcher $dispatcher, BettingProvider $betProvider)
    {
        $odds = fractal()
            ->collection($betProvider->getOdds(false), new ApiEventToOdds())
            ->toArray();

        $dispatcher->dispatch(new OddsUpdate($odds));
    }
}
