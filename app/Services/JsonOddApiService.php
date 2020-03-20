<?php
namespace App\Services;

class JsonOddApiService
{
    private $apiKey;

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

        return collect(json_decode($res, true))
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
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-api-key:' . $this->apiKey));
        $res = curl_exec($ch);

        return json_decode($res, true);
    }
}
