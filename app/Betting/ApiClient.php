<?php

namespace App\Betting;

use Illuminate\Support\Facades\Http;

class ApiClient
{
    private string $baseUrl;
    private string $authToken;

    public function __construct(string $baseUrl, string $authToken)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->authToken = $authToken;
    }

    public function getOddsData(): array
    {
        return $this->get('/api/v1/all');
    }

    private function get(string $url): array
    {
        $response = Http::get($this->baseUrl . $url . '?authtoken=' . $this->authToken);

        $data = $response->json();

        if ($response->failed() || empty($data)) {
            throw new \RuntimeException('Unable to communicate with API');
        }

        return $data;
    }
}
