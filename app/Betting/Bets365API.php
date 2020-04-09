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
        return Http::get("https://api.betsapi.com/v1/bet365/upcoming", [
            "page" => $page,
            "sport_id" => $sportId,
            "token" => $this->token,
        ])->json();
    }
}
