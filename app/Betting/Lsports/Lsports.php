<?php

namespace App\Betting\Lsports;

use App\Betting\BettingProvider;
use App\Betting\Pagination;
use App\Betting\Sport;
use App\Betting\SportEvent;
use App\Betting\SportEventOdd;
use App\Betting\SportEventResult;
use App\Betting\TimeStatus;
use App\Domain\ApiEvent;
use Decimal\Decimal;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Facades\Http;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

class Lsports implements BettingProvider
{
    public const PROVIDER_NAME = "lsports";
    public const PROVIDER_DESCRIPTION = 'L sports';
    private EntityManager $entityManager;
    private LoggerInterface $logger;
    private CacheInterface $cache;

    public function __construct(LoggerInterface $logger, EntityManager $entityManager, CacheInterface $cache)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->cache = $cache;
    }

    public function getEvents(int $page): Pagination
    {
        $data = $this->get('https://prematch.lsports.eu/OddService/GetFixtures');

        $fixtures = [];
        $total = 0;
        foreach($data['Body'] as $fixture) {
            if ($fixture['Fixture']['Status'] !== 1) {
                continue;
            }

            $fixtures[] = new SportEvent(
                $fixture['FixtureId'],
                $fixture['Fixture']['StartDate'],
                $fixture['Fixture']['Sport']['Id'],
                $fixture['Fixture']['Participants'][0]['Name'],
                $fixture['Fixture']['Participants'][1]['Name'],
                self::PROVIDER_NAME
            );
            $total++;
        }
        return new Pagination($fixtures, $total, $total);
    }

    public function getOdds(bool $updatesOnly): array
    {
        $data = $this->get('https://prematch.lsports.eu/OddService/GetFixtureMarkets');
        $updates = [];

        $parser = new Parser();

        foreach ($data['Body'] as $item) {
            /** @var ApiEvent|null $apiEvent */
            $apiEvent = current($this->entityManager->getRepository(ApiEvent::class)->findBy([
                'apiId' => $item['FixtureId'],
                'provider' => static::PROVIDER_NAME,
            ])) ?: null;

            if ($apiEvent === null) {
                continue;
            }

            if ($apiEvent->isFinished()) {
                continue;
            }

            $sportsOdds = $parser->parseMainLines(new FilterMarkets(new \ArrayIterator($item['Markets'])), $apiEvent->getSportId() === 154914);
            $apiEvent->updateOdds($sportsOdds);
            $updates[] = $apiEvent;
        }

        return $updates;
    }

    public function getResults(): array
    {
        $data = $this->get('https://prematch.lsports.eu/OddService/GetScores');

        $results = [];

        foreach($data['Body'] as $item) {
            /** @var ApiEvent|null $apiEvent */
            $apiEvent = current($this->entityManager->getRepository(ApiEvent::class)->findBy([
                'apiId' => $item['FixtureId'],
                'provider' => static::PROVIDER_NAME,
            ])) ?: null;

            if ($apiEvent === null) {
                continue;
            }

            $homeScore = $awayScore = null;

            foreach ($item['Livescore']['Scoreboard']['Results'] as $result) {
                if ($result['Position'] === '1') {
                    $homeScore = $result['Value'];
                }
                if ($result['Position'] === '2') {
                    $awayScore = $result['Value'];
                }
            }

            $results[] = new SportEventResult(
                $item['FixtureId'],
                self::PROVIDER_NAME,
                $this->mapTimeStatus($item['Livescore']['Scoreboard']['Status']),
                $homeScore,
                $awayScore
            );
        }

        return $results;
    }

    public function getSports(): array
    {
        $data = $this->get("https://prematch.lsports.eu/OddService/GetSports");

        return collect($data['Body'])
            ->map(fn ($sport) => new Sport($sport['Id'], $sport['Name'], self::PROVIDER_NAME))
            ->toArray();
    }

    protected function mapTimeStatus(string $status): TimeStatus
    {
        switch ($status) {
            case 1:
            case 9:
                return TimeStatus::NOT_STARTED();
            case 2:
            case 6:
            case 8:
                return TimeStatus::IN_PLAY();
            case 3:
                return TimeStatus::ENDED();
            case 4:
            case 5:
            case 7:
                return TimeStatus::CANCELED();
            default:
                return TimeStatus::IN_PLAY();
        }
    }

    private function get(string $url): array
    {
        $cacheKey = md5($url);

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $response = Http::get($url, [
            "username" => 'davidleighgrossman@gmail.com',
            "password" => 'f4654wcw',
            "guid" => 'af32c0ce-7083-4800-b447-793feb26c1dc',
        ]);

        $data = $response->json();

        if ($response->failed() || !isset($data['Body']) || isset($data['Errors'])) {
            $this->logger->info('Unable to communicate with API', $data);
            throw new \RuntimeException('Unable to communicate with API');
        }

        $this->cache->set($cacheKey, $data, 60);

        return $data;
    }
}
