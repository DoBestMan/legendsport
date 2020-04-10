<?php
namespace App\Betting;

class JsonOddAPI
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

        return json_decode($res, true);
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

        return json_decode($res, true);
    }
}
