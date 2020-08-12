<?php

namespace App\Betting\SportsData;

use App\Betting\MultiProvider;
use App\Betting\SingleEventUpdater;
use App\Domain\ApiEvent;
use Doctrine\ORM\EntityManager;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateOddsJob implements ShouldQueue
{
    private int $apiEventId;

    public function __construct(int $apiEventId)
    {
        $this->apiEventId = $apiEventId;
    }

    public function handle(EntityManager $entityManager, MultiProvider $provider)
    {
        /** @var ApiEvent $apiEvent */
        $apiEvent = $entityManager->find(ApiEvent::class, $this->apiEventId);
        $bettingProvider = $provider->getProvider($apiEvent->getProvider());

        if ($bettingProvider instanceof SingleEventUpdater) {
            $bettingProvider->updateEventOdds($apiEvent);
        }

        $entityManager->flush();
    }
}
