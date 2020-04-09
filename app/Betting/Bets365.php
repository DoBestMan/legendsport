<?php
namespace App\Betting;

class Bets365 implements BettingProvider
{
    private Bets365API $api;

    public function __construct(Bets365API $api)
    {
        $this->api = $api;
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
                ),
            )
            ->all();

        return new Pagination($results, $data["pager"]["total"], $data["pager"]["per_page"]);
    }

    public function getOdds(): array
    {
        // TODO: Implement getOdds() method.
        return [];
    }

    public function getSports(): array
    {
        // https://betsapi.com/docs/GLOSSARY.html#r-sportid
        return [new Sport("1", "Soccer"), new Sport("151", "E-sports")];
    }
}
