<?php
namespace App\Services;

use Psr\SimpleCache\CacheInterface;

class OddService
{
    private const CACHE_TTL = 10 * 60;
    private const CACHE_KEY = "api_odds";
    private CacheInterface $cache;
    private JsonOddApiService $jsonOddApiService;

    public function __construct(CacheInterface $cache, JsonOddApiService $jsonOddApiService)
    {
        $this->cache = $cache;
        $this->jsonOddApiService = $jsonOddApiService;
    }

    public function getOdds(): array
    {
        $odds = $this->cache->get(OddService::CACHE_KEY);

        if ($odds) {
            return $odds;
        }

        $odds = $this->jsonOddApiService->getOdds();
        $this->cache->set(OddService::CACHE_KEY, $odds, OddService::CACHE_TTL);

        return $odds;
    }
}
