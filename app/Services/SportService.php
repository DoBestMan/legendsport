<?php
namespace App\Services;

use App\Exceptions\LimitExceededException;
use Illuminate\Support\Arr;
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

        if (!$sports) {
            $sports = $this->jsonOddApiService->getSports();
            $this->cache->set(SportService::CACHE_KEY, $sports, SportService::CACHE_TTL);
        }

        if (Arr::get($sports, "message") === "Limit Exceeded") {
            throw new LimitExceededException();
        }

        return collect($sports)
            ->map(
                fn($value, $key) => [
                    "id" => $key,
                    "name" => strtoupper($value),
                ],
            )
            ->values()
            ->all();
    }
}
