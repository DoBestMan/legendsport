<?php

namespace App\Jobs\Tournaments;

use App\Betting\MultiProvider;
use App\Http\Transformers\App\ApiEventToOdds;
use App\Queue\Uniqueable;
use App\Tournament\Events\OddsUpdate;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Psr\Log\LoggerInterface;

class UpdateApiDataJob implements ShouldQueue, Uniqueable
{
    private int $offset;
    public \DateTime $delay;

    public function __construct(int $offset)
    {
        $this->delay = Carbon::now()->addSeconds($offset);
        $this->offset = $offset;
    }

    public function handle(
        MultiProvider $bettingProvider,
        LoggerInterface $logger,
        Dispatcher $dispatcher
    ) {
        $logger->info('Updating API Events');

        $apiEvents = $bettingProvider->updateEvents();

        $odds = fractal()
            ->collection($apiEvents, new ApiEventToOdds())
            ->toArray();

        $dispatcher->dispatch(new OddsUpdate($odds, false));

        $logger->info('API Events Updated');
    }

    public function uniqueable()
    {
        return hash('sha256', static::class . '(' . $this->offset . ')');
    }
}
