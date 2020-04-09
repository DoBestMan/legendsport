<?php
namespace App\Jobs;

use App\Http\Transformers\App\EventOddTransformer;
use App\SportEvent\OddsProvider;
use App\Tournament\Events\OddsUpdate;
use Illuminate\Contracts\Events\Dispatcher;

class UpdateOdds
{
    public function handle(Dispatcher $dispatcher, OddsProvider $oddsProvider)
    {
        $odds = fractal()
            ->collection($oddsProvider->getOdds(), new EventOddTransformer())
            ->toArray();

        $dispatcher->dispatch(new OddsUpdate($odds));
    }
}
