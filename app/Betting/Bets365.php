<?php
namespace App\Betting;

use React\Cache\CacheInterface;

class Bets365 implements BettingProvider
{
    private CacheInterface $cache;
    private Bets365API $api;

    public function __construct(CacheInterface $cache, Bets365API $api)
    {
        $this->cache = $cache;
        $this->api = $api;
    }

    public function getEvents(int $page): array
    {
        // 151 - esport
        $sportId = "151";

        return collect($this->api->getUpcomingEvents($sportId))
            ->map(
                fn(array $data) => new SportEvent(
                    null,
                    $data["id"],
                    $data["time"],
                    $sportId,
                    $data["home"]["name"],
                    $data["away"]["name"],
                ),
            )
            ->all();
    }

    public function getOdds(): array
    {
        // TODO: Implement getOdds() method.
        return [];
    }

    public function getSports(): array
    {
        // TODO: Implement getSports() method.
        return [];
    }
}
