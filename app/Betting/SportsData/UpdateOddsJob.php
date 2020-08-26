<?php

namespace App\Betting\SportsData;

use App\Betting\MultiProvider;
use App\Betting\SingleEventUpdater;
use App\Domain\ApiEvent;
use App\Http\Transformers\App\ApiEventToOdds;
use App\Queue\Uniqueable;
use App\Tournament\Events\OddsUpdate;
use Doctrine\ORM\EntityManager;
use Illuminate\Events\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateOddsJob implements ShouldQueue, Uniqueable
{
    private int $apiEventId;

    public function __construct(int $apiEventId)
    {
        $this->apiEventId = $apiEventId;
    }

    public function handle(EntityManager $entityManager, MultiProvider $provider, Dispatcher $dispatcher)
    {
        /** @var ApiEvent $apiEvent */
        $apiEvent = $entityManager->find(ApiEvent::class, $this->apiEventId);
        $bettingProvider = $provider->getProvider($apiEvent->getProvider());

        if ($bettingProvider instanceof SingleEventUpdater) {
            $bettingProvider->updateEventOdds($apiEvent);
        }

        $entityManager->flush();

        $odds = fractal()
            ->collection([$apiEvent], new ApiEventToOdds())
            ->toArray();

        $dispatcher->dispatch(new OddsUpdate($odds, true));
    }

    public function uniqueable()
    {
        return hash('sha256', static::class . '(' . $this->apiEventId . ')');
    }
}
