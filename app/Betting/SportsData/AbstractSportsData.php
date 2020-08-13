<?php

namespace App\Betting\SportsData;

use App\Betting\BettingProvider;
use Doctrine\ORM\EntityManager;
use Illuminate\Bus\Dispatcher;
use Illuminate\Support\Facades\Http;
use Psr\Log\LoggerInterface;

abstract class AbstractSportsData implements BettingProvider
{
    protected EntityManager $entityManager;
    protected Parser $parser;
    protected Dispatcher $dispatcher;
    private string $apiKey;
    protected LoggerInterface $logger;

    public function __construct(string $apiKey, EntityManager $entityManager, LoggerInterface $logger, Dispatcher $dispatcher)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->apiKey = $apiKey;
        $this->parser = new Parser();
        $this->dispatcher = $dispatcher;
    }

    public function get(string $url): array
    {
        $result = Http::withHeaders([
            'Ocp-Apim-Subscription-Key' => $this->apiKey,
        ])->timeout(60)->get($url);

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
