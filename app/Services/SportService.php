<?php
namespace App\Services;

use Psr\SimpleCache\CacheInterface;

class SportService
{
    private const CACHE_TTL = 24 * 60 * 60;
    private const CACHE_KEY = "api_sports";
    private CacheInterface $cache;
    private JsonOddApiService $jsonOddApiService;

    public function __construct(CacheInterface $cache, JsonOddApiService $jsonOddApiService)
    {
        $this->cache = $cache;
        $this->jsonOddApiService = $jsonOddApiService;
    }

    public function getSports(): array
    {
        $sports = $this->cache->get(SportService::CACHE_KEY);

        if ($sports) {
            return $sports;
        }

        $sports = $this->jsonOddApiService->getSports();
        $this->cache->set(SportService::CACHE_KEY, $sports, SportService::CACHE_TTL);

        return $sports;
    }
}
