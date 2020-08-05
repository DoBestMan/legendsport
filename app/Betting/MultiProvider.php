<?php

namespace App\Betting;

use App\Models\Config;
use Doctrine\ORM\EntityManager;

class MultiProvider implements BettingProvider
{
    private EntityManager $entityManager;
    /** @var BettingProvider[] */
    private array $providers;

    public function __construct(EntityManager $entityManager, BettingProvider ...$providers)
    {
        $this->entityManager = $entityManager;
        foreach ($providers as $provider) {
            $this->providers[$provider::PROVIDER_NAME] = $provider;
        }
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

    public function getOdds(bool $updatesOnly): array
    {
        $odds = [];
        foreach ($this->getEnabledProviders() as $provider) {
            $odds = array_merge($odds, $provider->getOdds($updatesOnly));
        }

        return $odds;
    }

    public function getResults(): array
    {
        $results = [];
        foreach ($this->getEnabledProviders() as $provider) {
            $results = array_merge($results, $provider->getResults());
        }

        return $results;
    }

    public function getSports(): array
    {
        $sports = [];
        foreach ($this->getEnabledProviders() as $provider) {
            $sports = array_merge($sports, $provider->getSports());
        }

        return $sports;
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
