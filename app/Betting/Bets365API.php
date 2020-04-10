<?php
namespace App\Betting;

use Illuminate\Support\Facades\Http;

class Bets365API
{
    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function getUpcomingEvents(string $sportId, int $page): array
    {
        $response = Http::get("https://api.betsapi.com/v1/bet365/upcoming", [
            "page" => $page,
            "sport_id" => $sportId,
            "token" => $this->token,
        ]);

        return $response->json();
    }

    public function getPreMatchOdds(iterable $ids)
    {
        $ids = collect($ids)->slice(0, 11);

        if ($ids->isEmpty()) {
            return [];
        }

        $response = Http::get("https://api.betsapi.com/v1/bet365/prematch", [
            "token" => $this->token,
            "FI" => $ids->implode(","),
        ]);

        return $response->json()["results"];
    }
}
