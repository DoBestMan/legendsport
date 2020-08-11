<?php

namespace App\Betting\SportsData;

use App\Betting\BettingProvider;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Facades\Http;
use Psr\Log\LoggerInterface;

abstract class AbstractSportsData implements BettingProvider
{
    protected EntityManager $entityManager;
    private string $apiKey;
    protected LoggerInterface $logger;

    public function __construct(string $apiKey, EntityManager $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->apiKey = $apiKey;
    }

    public function get(string $url): array
    {
        $result = Http::withHeaders([
            'Ocp-Apim-Subscription-Key' => $this->apiKey,
        ])->get($url);

        $data = $result->json();

        if (!$result->successful()) {
            $message = '';
            if (isset($data['message'])) {
                $message = $data['message'];
            }

            throw new \RuntimeException(sprintf('Unable to communicate with API (%s): %s', $url, $message));
        }

        return $data;
    }
}
