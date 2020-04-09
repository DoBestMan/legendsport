<?php
namespace App\Betting;

use App\Exceptions\LimitExceededException;
use App\Services\JsonOddApiService;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Psr\SimpleCache\CacheInterface;

class JsonOddAPI implements BettingProvider
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

    public function getEvents(): array
    {
        return collect($this->getRawOdds())
            ->map(
                fn(array $odds) => new SportEvent(
                    $odds["ID"],
                    new Carbon($odds["MatchTime"]),
                    $odds["Sport"],
                    $odds["HomeTeam"],
                    $odds["AwayTeam"],
                ),
            )
            ->all();
    }

    public function getOdds(): array
    {
        return collect($this->getRawOdds())
            ->map(fn(array $event) => $event["Odds"][0])
            ->map(
                fn(array $odds) => new SportEventOdd(
                    $odds["EventID"],
                    $odds["MoneyLineHome"],
                    $odds["MoneyLineAway"],
                    $odds["PointSpreadHome"],
                    $odds["PointSpreadAway"],
                    $odds["PointSpreadHomeLine"],
                    $odds["PointSpreadAwayLine"],
                    $odds["OverLine"],
                    $odds["UnderLine"],
                    $odds["TotalNumber"],
                ),
            )
            ->all();
    }

    private function getRawOdds(): array
    {
        $odds = $this->cache->get(JsonOddAPI::CACHE_KEY);

        if (!$odds) {
            $odds = $this->jsonOddApiService->getOdds();
            $this->cache->set(JsonOddAPI::CACHE_KEY, $odds, JsonOddAPI::CACHE_TTL);
        }

        if (Arr::get($odds, "message") === "Limit Exceeded") {
            throw new LimitExceededException();
        }

        return $odds;
    }
}
