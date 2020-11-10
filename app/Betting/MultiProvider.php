<?php

namespace App\Betting;

use App\Betting\SportEvent\UpdateCollection;
use App\Domain\ApiEvent;
use App\Jobs\Publishers\PublishTournamentUpdate;
use App\Jobs\Tournaments\GradeEvent;
use App\Models\Config;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Illuminate\Bus\Dispatcher;
use Psr\Log\LoggerInterface;

class MultiProvider implements BettingProvider
{
    private EntityManager $entityManager;
    /** @var BettingProvider[] */
    private array $providers = [];
    private array $providerMap = [];
    private LoggerInterface $logger;
    private Dispatcher $dispatcher;

    public function __construct(
        EntityManager $entityManager,
        LoggerInterface $logger,
        Dispatcher $dispatcher,
        BettingProvider ...$providers
    ) {
        $this->entityManager = $entityManager;
        foreach ($providers as $provider) {
            $this->providers[$provider::PROVIDER_NAME] = $provider;
            $this->providerMap[$provider::PROVIDER_NAME] = $provider::PROVIDER_DESCRIPTION;
        }
        $this->logger = $logger;
        $this->dispatcher = $dispatcher;
    }

    public function getProvider(string $name): BettingProvider
    {
        return $this->providers[$name];
    }

    public function getProviderMap(): array
    {
        return $this->providerMap;
    }

    public function getEvents(int $page): Pagination
    {
        $pagination = new Pagination([], 0, 0);
        foreach ($this->getEnabledProviders() as $provider) {
            $events = $provider->getEvents($page);
            $pagination = new Pagination(
                array_merge($pagination->getResults(), $events->getResults()),
                $pagination->getTotal() + $events->getTotal(),
                $pagination->getPerPage() + $events->getPerPage()
            );
        }

        return $pagination;
    }

    public function getSports(): array
    {
        $sports = [];
        foreach ($this->getEnabledProviders() as $provider) {
            $sports = array_merge($sports, $provider->getSports());
        }

        return $sports;
    }

    public function getUpdates(): UpdateCollection
    {
        $updates = [];
        foreach ($this->getEnabledProviders() as $provider) {
            $updates = array_merge($updates, $provider->getUpdates()->getUpdates());
        }

        return new UpdateCollection('mixed', ...$updates);
    }

    public function updateEvents()
    {
        $activeApiEvents = [];

        foreach ($this->getEnabledProviders() as $provider) {
            $updateCollection = $provider->getUpdates();
            $criteria = Criteria::create()
                ->where(Criteria::expr()->in('apiId', $updateCollection->getExternalIds()))
                ->andWhere(Criteria::expr()->eq('provider', $updateCollection->getProvider()));

            $this->entityManager->beginTransaction();
            /** @var ApiEvent[]|Collection $apiEvents */
            $apiEvents = $this->entityManager->getRepository(ApiEvent::class)->matching($criteria);

            foreach ($apiEvents as $apiEvent) {
                if (!$updateCollection->hasUpdate($apiEvent->getApiId())) {
                    continue;
                }

                $update = $updateCollection->getUpdate($apiEvent->getApiId());
                $result = $apiEvent->update($update);

                if (!$result->hasUpdated()) {
                    continue;
                }

                $this->logger->info("Api event has been updated.", [
                    "api_event_id" => $apiEvent->getId(),
                    "api_event_external_id" => $apiEvent->getApiId(),
                    "score_home" => $apiEvent->getScoreHome(),
                    "score_away" => $apiEvent->getScoreAway(),
                    "time_status" => $apiEvent->getTimeStatus(),
                ]);

                foreach ($apiEvent->getTournamentEvents() as $tournamentEvent) {
                    $tournament = $tournamentEvent->getTournament();

                    if ($result->hasLinesToGrade()) {
                        $this->dispatcher->dispatch(new GradeEvent($tournament->getId(), $tournamentEvent->getId()));
                    }

                    $this->dispatcher->dispatch(new PublishTournamentUpdate($tournament->getId()));
                }
            }

            $activeApiEvents = array_merge($activeApiEvents, $apiEvents->toArray());

            $this->entityManager->flush();
            $this->entityManager->commit();
        }

        return $activeApiEvents;
    }

    private function getEnabledProviders(): array
    {
        $enabledProviders = [];

        //@Todo replace with doctrine model once refactoring is done
        $config = Config::first();
        foreach ($config->config['providers'] as $provider) {
            $enabledProviders[] = $this->providers[$provider];
        }

        return $enabledProviders;
    }
}
