<?php

namespace App\Jobs\Tournaments;

use App\Betting\MultiProvider;
use App\Http\Transformers\App\ApiEventToOdds;
use App\Queue\Uniqueable;
use App\Tournament\Events\OddsUpdate;
use App\Tournament\SportEventResultProcessor;
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
        SportEventResultProcessor $sportEventResultProcessor,
        LoggerInterface $logger,
        Dispatcher $dispatcher
    ) {
        $logger->info('Fetching odds for games');

        $apiEvents = $bettingProvider->getOdds(false);

        $odds = fractal()
            ->collection($apiEvents, new ApiEventToOdds())
            ->toArray();

        $logger->info('Odds updated');
        $dispatcher->dispatch(new OddsUpdate($odds, false));

        $logger->info('Fetching game results');

        $results = $bettingProvider->getResults();

        try {
            $sportEventResultProcessor->processMultiple($results);
        } catch (\Throwable $e) {
            $logger->error($e->getMessage(), ["exception" => $e]);
        }
    }

    public function uniqueable()
    {
        return hash('sha256', static::class . '(' . $this->offset . ')');
    }
}
