<?php
namespace App\Betting;

use App\Exceptions\LimitExceededException;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Psr\SimpleCache\CacheInterface;

class JsonOddAPI implements BettingProvider
{
    private const ODDS_CACHE_TTL = 10 * 60;
    private const ODDS_CACHE_KEY = "api_odds";
    private const SPORTS_CACHE_TTL = 24 * 60 * 60;
    private const SPORTS_CACHE_KEY = "api_sports";
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
                    null,
                    $odds["ID"],
                    $odds["MatchTime"] ? new Carbon($odds["MatchTime"]) : null,
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

    public function getSports(): array
    {
        return collect($this->getRawSports())
            ->map(fn($value, $key) => new Sport($key, $value))
            ->all();
    }

    private function getRawOdds(): array
    {
        $odds = $this->cache->get(JsonOddAPI::ODDS_CACHE_KEY);

        if (!$odds) {
            $odds = $this->jsonOddApiService->getOdds();
            $this->cache->set(JsonOddAPI::ODDS_CACHE_KEY, $odds, JsonOddAPI::ODDS_CACHE_TTL);
        }

        if (Arr::get($odds, "message") === "Limit Exceeded") {
            throw new LimitExceededException();
        }

        return $odds;
    }

    private function getRawSports(): array
    {
        $sports = $this->cache->get(JsonOddAPI::SPORTS_CACHE_KEY);

        if (!$sports) {
            $sports = $this->jsonOddApiService->getSports();
            $this->cache->set(JsonOddAPI::SPORTS_CACHE_KEY, $sports, JsonOddAPI::SPORTS_CACHE_TTL);
        }

        if (Arr::get($sports, "message") === "Limit Exceeded") {
            throw new LimitExceededException();
        }

        return $sports;
    }
}
