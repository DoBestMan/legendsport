<?php
namespace App\Betting;

use App\Exceptions\LimitExceededException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

// Various constants description: https://betsapi.com/docs/GLOSSARY.html

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
        $data = $response->json();

        if (Arr::get($data, "error") === "PERMISSION_DENIED") {
            throw new LimitExceededException();
        }

        return $data;
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

        $data = $response->json();

        if (Arr::get($data, "error") === "PERMISSION_DENIED") {
            throw new LimitExceededException();
        }

        return $data["results"];
    }

    public function getResults(iterable $ids)
    {
        $ids = collect($ids)->slice(0, 11);

        if ($ids->isEmpty()) {
            return [];
        }

        $response = Http::get("https://api.betsapi.com/v1/bet365/result", [
            "token" => $this->token,
            "event_id" => $ids->implode(","),
        ]);
        $data = $response->json();

        if (Arr::get($data, "error") === "PERMISSION_DENIED") {
            throw new LimitExceededException();
        }

        return $data["results"];
    }
}
