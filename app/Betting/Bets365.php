<?php
namespace App\Betting;

use App\Models\TournamentEvent;
use Arr;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Psr\SimpleCache\CacheInterface;

class Bets365 implements BettingProvider
{
    const PROVIDER_NAME = "bet365";

    private Bets365API $api;
    private CacheInterface $cache;

    public function __construct(CacheInterface $cache, Bets365API $api)
    {
        $this->api = $api;
        $this->cache = $cache;
    }

    public function getEvents(int $page): Pagination
    {
        // 151 - esport
        $sportId = "151";

        $data = $this->api->getUpcomingEvents($sportId, $page);

        $results = collect($data["results"])
            ->map(
                fn(array $item) => new SportEvent(
                    null,
                    $item["id"],
                    (int) $item["time"],
                    $sportId,
                    $item["home"]["name"],
                    $item["away"]["name"],
                    static::PROVIDER_NAME,
                ),
            )
            ->all();

        return new Pagination($results, $data["pager"]["total"], $data["pager"]["per_page"]);
    }

    public function getOdds(): array
    {
        return $this->getPreMatchOdds()->all();
    }

    public function getSports(): array
    {
        // https://betsapi.com/docs/GLOSSARY.html#r-sportid
        return [new Sport("1", "Soccer"), new Sport("151", "E-sports")];
    }

    /**
     * @return SportEventOdd[]|Collection
     */
    public function getPreMatchOdds(): Collection
    {
        // Get all upcoming tournaments' events
        $sportEventDict = TournamentEvent::with(["apiEvent"])
            ->get()
            ->map(fn(TournamentEvent $tournamentEvent) => $tournamentEvent->apiEvent->api_data)
            ->filter(function (SportEvent $sportEvent) {
                $startsAt = $sportEvent->getStartsAt();
                return $sportEvent->getProvider() === static::PROVIDER_NAME &&
                    $startsAt &&
                    $startsAt->greaterThan(Carbon::now());
            })
            ->mapWithKeys(
                fn(SportEvent $sportEvent) => [$sportEvent->getExternalId() => $sportEvent],
            );

        $preMatchOdds = collect();
        $eventIdsToRequest = collect();
        $cacheKeys = $sportEventDict
            ->keys()
            ->map(fn(string $key) => $this->createPreMatchKey($key));

        // Try to restore data from cache
        foreach ($this->cache->getMultiple($cacheKeys) as $eventId => $event) {
            if ($event) {
                $preMatchOdds->push($event);
            } else {
                $eventIdsToRequest->push($eventId);
            }
        }

        // Call API for data in 10-elements chunks
        foreach ($eventIdsToRequest->chunk(10) as $keys) {
            $results = collect($this->api->getPreMatchOdds($keys->values()));
            $this->cache->setMultiple(
                $results->mapWithKeys(
                    fn(array $item) => [$this->createPreMatchKey($item['FI']) => $item],
                ),
                12 * 60 * 60,
            );
            $preMatchOdds->push(...$results);
        }

        // Map API items into SportEventOdds
        return collect($preMatchOdds)->map(function (array $eventItem) use ($sportEventDict) {
            $eventId = $eventItem["FI"];
            $matchLine = Arr::get($eventItem, "main.sp.match_lines") ?? [];

            /** @var SportEvent $sportEvent */
            $sportEvent = $sportEventDict[$eventId];

            $moneyLineHome = null;
            $moneyLineAway = null;
            $pointSpreadHome = null;
            $pointSpreadAway = null;
            $pointSpreadHomeLine = null;
            $pointSpreadAwayLine = null;
            $overLine = null;
            $underLine = null;
            $totalNumber = null;

            foreach ($matchLine as $item) {
                $teamName = Arr::get($item, "header");
                $type = Arr::get($item, "name");
                $odds = decimal_to_american(Arr::get($item, "odds"));
                $handicap = as_decimal(Arr::get($item, "handicap"));

                if ($type === "To Win" && $this->cmpStr($teamName, $sportEvent->getHomeTeam())) {
                    $moneyLineHome = $odds;
                } elseif (
                    $type === "To Win" &&
                    $this->cmpStr($teamName, $sportEvent->getAwayTeam())
                ) {
                    $moneyLineAway = $odds;
                } elseif (
                    $type === "Handicap" &&
                    $this->cmpStr($teamName, $sportEvent->getHomeTeam())
                ) {
                    $pointSpreadHome = $odds;
                    $pointSpreadHomeLine = $handicap;
                } elseif (
                    $type === "Handicap" &&
                    $this->cmpStr($teamName, $sportEvent->getAwayTeam())
                ) {
                    $pointSpreadAway = $odds;
                    $pointSpreadAwayLine = $handicap;
                }
            }

            return new SportEventOdd(
                $eventId,
                $moneyLineHome,
                $moneyLineAway,
                $pointSpreadHome,
                $pointSpreadAway,
                $pointSpreadHomeLine,
                $pointSpreadAwayLine,
                $overLine,
                $underLine,
                $totalNumber,
            );
        });
    }

    private function createPreMatchKey(string $eventId): string
    {
        return "bets365.prematch.{$eventId}";
    }

    private function cmpStr(string $a, string $b): bool
    {
        return strtolower(trim($a)) === strtolower(trim($b));
    }
}
