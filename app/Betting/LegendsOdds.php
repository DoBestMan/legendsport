<?php

namespace App\Betting;

use App\Domain\ApiEvent;
use Decimal\Decimal;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Facades\Http;

class LegendsOdds implements BettingProvider
{
    public const PROVIDER_NAME = "legends-odds";
    public const PROVIDER_DESCRIPTION = 'Legends Odds';

    private EntityManager $entityManager;
    private string $baseUrl;
    private string $authToken;

    public function __construct(EntityManager $entityManager, string $baseUrl, string $authToken)
    {
        $this->entityManager = $entityManager;
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->authToken = $authToken;
    }

    public function getEvents(int $page): Pagination
    {
        $results = $this->get('/api/v1/all');
        $events = [];
        foreach ($results as $result) {
            if ($result['status'] !== 'upcoming') {
                continue;
            }
            $events[] = new SportEvent(
                $result['id'],
                $result['startDate'],
                $result['sportId'],
                $result['homeTeam'],
                $result['awayTeam'],
                self::PROVIDER_NAME
            );
        }

        usort($events, fn (SportEvent $a, SportEvent $b) => $a->getStartsAt() <=> $b->getStartsAt());

        $total = count($events);
        return new Pagination($events, $total, $total);
    }

    public function getOdds(bool $updatesOnly): array
    {
        $data = $this->get('/api/v1/all');
        $updates = [];

        foreach ($data as $item) {
            /** @var ApiEvent|null $apiEvent */
            $apiEvent = current($this->entityManager->getRepository(ApiEvent::class)->findBy([
                'apiId' => $item['id'],
                'provider' => static::PROVIDER_NAME,
            ])) ?: null;

            if ($apiEvent === null) {
                continue;
            }

            if ($apiEvent->isFinished()) {
                continue;
            }

            $sportsOdds= new SportEventOdd(
                $item['id'],
                decimal_to_american($item['moneylineHome']),
                decimal_to_american($item['moneylineAway']),
                decimal_to_american($item['spreadHome']),
                decimal_to_american($item['spreadAway']),
                $item['handicapHome'] ? new Decimal(explode(' ', $item['handicapHome'])[0]) : null,
                $item['handicapAway'] ? new Decimal(explode(' ', $item['handicapAway'])[0]) : null,
                decimal_to_american($item['over']),
                decimal_to_american($item['under']),
                $item['total'] ? new Decimal($item['total']) : null,
            );
            $apiEvent->updateOdds($sportsOdds);
            $updates[] = $apiEvent;
        }

        $this->entityManager->flush();

        return $updates;
    }

    public function getResults(): array
    {
        $data = $this->get('/api/v1/all');

        $results = [];

        foreach($data as $item) {
            /** @var ApiEvent|null $apiEvent */
            $apiEvent = current($this->entityManager->getRepository(ApiEvent::class)->findBy([
                'apiId' => $item['id'],
                'provider' => static::PROVIDER_NAME,
            ])) ?: null;

            if ($apiEvent === null) {
                continue;
            }

            $results[] = new SportEventResult(
                $item['id'],
                self::PROVIDER_NAME,
                $this->mapTimeStatus($item['status']),
                $item['startDate'],
                $item['homeScore'],
                $item['awayScore']
            );
        }

        return $results;
    }

    public function getSports(): array
    {
        return [
            //new Sport('6046', 'Football', self::PROVIDER_NAME),
            //new Sport('54094', 'Tennis', self::PROVIDER_NAME),
            //new Sport('530129', 'Hockey', self::PROVIDER_NAME),
            new Sport('131506', 'American Football', self::PROVIDER_NAME),
            new Sport('154914', 'Baseball', self::PROVIDER_NAME),
            new Sport('48242', 'Basketball', self::PROVIDER_NAME),
            new Sport('35232', 'Ice Hockey', self::PROVIDER_NAME),
        ];
    }

    private function get(string $url): array
    {
        $response = Http::get($this->baseUrl . $url . '?authtoken=' . $this->authToken);

        $data = $response->json();

        if ($response->failed() || empty($data)) {
            //$this->logger->info('Unable to communicate with API', $data);
            throw new \RuntimeException('Unable to communicate with API');
        }

        return $data;
    }

    protected function mapTimeStatus(string $status): TimeStatus
    {
        switch ($status) {
            case 'upcoming':
                return TimeStatus::NOT_STARTED();
            case 'inplay':
                return TimeStatus::IN_PLAY();
            case 'ended':
                return TimeStatus::ENDED();
            case 'cancelled':
                return TimeStatus::CANCELED();
            default:
                return TimeStatus::IN_PLAY();
        }
    }
}
