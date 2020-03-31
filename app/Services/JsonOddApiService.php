<?php
namespace App\Services;

use App\Exceptions\LimitExceededException;

class JsonOddApiService
{
    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getSports(): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://jsonodds.com/api/sports');
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['x-api-key:' . $this->apiKey]);
        $res = curl_exec($ch);

        $response = json_decode($res, true);

        if ($response["message"] === "Limit Exceeded") {
            throw new LimitExceededException();
        }

        return collect($response)
            ->map(
                fn($value, $key) => [
                    "id" => $key,
                    "name" => strtoupper($value),
                ],
            )
            ->values()
            ->all();
    }

    public function getOdds(): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://jsonodds.com/api/odds/all');
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['x-api-key:' . $this->apiKey]);
        $res = curl_exec($ch);

        $response = json_decode($res, true);

        if ($response["message"] === "Limit Exceeded") {
            throw new LimitExceededException();
        }

        return $response;
    }
}
