<?php
namespace App\Betting;

use App\Exceptions\LimitExceededException;
use Illuminate\Support\Arr;
use Psr\SimpleCache\CacheInterface;

class JsonOdd implements BettingProvider
{
    const PROVIDER_NAME = "jsonodd";
    private const ODDS_CACHE_TTL = 10 * 60;
    private const ODDS_CACHE_KEY = "jsonodds_odds";
    private const SPORTS_CACHE_TTL = 24 * 60 * 60;
    private const SPORTS_CACHE_KEY = "jsonodds_sports";

    private CacheInterface $cache;
    private JsonOddAPI $api;

    public function __construct(CacheInterface $cache, JsonOddAPI $api)
    {
        $this->cache = $cache;
        $this->api = $api;
    }

    public function getEvents(int $page): Pagination
    {
        $results = collect($this->getRawOdds())
            ->map(
                fn(array $odds) => new SportEvent(
                    $odds["ID"],
                    $odds["MatchTime"],
                    $odds["Sport"],
                    $odds["HomeTeam"],
                    $odds["AwayTeam"],
                    static::PROVIDER_NAME,
                ),
            )
            ->all();

        return new Pagination($results, count($results), count($results));
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
                    as_decimal($odds["PointSpreadHomeLine"]),
                    as_decimal($odds["PointSpreadAwayLine"]),
                    $odds["OverLine"],
                    $odds["UnderLine"],
                    as_decimal($odds["TotalNumber"]),
                ),
            )
            ->all();
    }

    public function getResults(): array
    {
        return [];
    }

    public function getSports(): array
    {
        return collect($this->getRawSports())
            ->map(fn($value, $key) => new Sport($key, $value))
            ->all();
    }

    private function getRawOdds(): array
    {
        $odds = $this->cache->get(JsonOdd::ODDS_CACHE_KEY);

        if (!$odds) {
            $odds = $this->api->getOdds();
            $this->cache->set(JsonOdd::ODDS_CACHE_KEY, $odds, JsonOdd::ODDS_CACHE_TTL);
        }

        if (Arr::get($odds, "message") === "Limit Exceeded") {
            throw new LimitExceededException();
        }

        return $odds;
    }

    private function getRawSports(): array
    {
        $sports = $this->cache->get(JsonOdd::SPORTS_CACHE_KEY);

        if (!$sports) {
            $sports = $this->api->getSports();
            $this->cache->set(JsonOdd::SPORTS_CACHE_KEY, $sports, JsonOdd::SPORTS_CACHE_TTL);
        }

        if (Arr::get($sports, "message") === "Limit Exceeded") {
            throw new LimitExceededException();
        }

        return $sports;
    }
}
