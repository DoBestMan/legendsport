<?php

namespace App\Betting;

use App\Betting\SportEvent\Event;
use App\Betting\SportEvent\LineCollection;
use App\Betting\SportEvent\Offer;
use App\Betting\SportEvent\Line;
use App\Betting\SportEvent\OfferCollection;
use App\Betting\SportEvent\Result;
use App\Betting\SportEvent\Sport;
use App\Betting\SportEvent\Update;
use App\Betting\SportEvent\UpdateCollection;
use App\Domain\Odds;
use Decimal\Decimal;

class LegendsOdds implements BettingProvider
{
    public const PROVIDER_NAME = "legends-odds";
    public const PROVIDER_DESCRIPTION = 'Legends Odds';

    private ApiClient $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
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

    public function getEvents(int $page = 0): Pagination
    {
        $results = $this->apiClient->getOddsData();
        $events = [];
        foreach ($results as $result) {
            if ($result['status'] !== 'upcoming') {
                continue;
            }
            $events[] = new Event(
                $result['id'],
                $result['startDate'],
                $result['sportId'],
                $result['homeTeam'],
                $result['awayTeam'],
                self::PROVIDER_NAME,
                $result['homePitcher'] ?? null,
                $result['awayPitcher'] ?? null,
            );
        }
        usort($events, fn (Event $a, Event $b) => $a->getStartsAt() <=> $b->getStartsAt());

        $total = count($events);
        return new Pagination($events, $total, $total);
    }

    public function getUpdates(): UpdateCollection
    {
        $data = $this->apiClient->getOddsData();

        foreach($data as $item) {
            $result = new Result(
                $item['id'],
                self::PROVIDER_NAME,
                TimeStatus::fromApiStatus($item['status']),
                $item['startDate'],
                $item['homeScore'],
                $item['awayScore'],
                $item['homePitcher'],
                $item['awayPitcher'],
            );

            $lines = [];
            $lineOffers = [];

            if (isset($item['lines'])) {
                foreach ($item['lines'] as $lineId => $line) {
                    $lines[] = new Line(
                        $lineId,
                        Odds::decimalToAmerican($line['price']),
                        isset($line['line']) ? new Decimal(explode(' ', $line['line'])[0]) : null,
                        Settlement::fromApiSettlement($line['settlement'])
                    );
                }

                $lineOffers[] = new Offer(
                    $item['moneylineHomeId'],
                    Offer::MONEYLINE, Offer::HOME, Offer::FULL_TIME
                );

                $lineOffers[] = new Offer(
                    $item['moneylineAwayId'],
                    Offer::MONEYLINE, Offer::AWAY, Offer::FULL_TIME
                );

                $lineOffers[] = new Offer(
                    $item['spreadHomeId'],
                    Offer::SPREAD, Offer::HOME, Offer::FULL_TIME
                );

                $lineOffers[] = new Offer(
                    $item['spreadAwayId'],
                    Offer::SPREAD, Offer::AWAY, Offer::FULL_TIME
                );

                $lineOffers[] = new Offer(
                    $item['overId'],
                    Offer::TOTAL, Offer::OVER, Offer::FULL_TIME
                );

                $lineOffers[] = new Offer(
                    $item['underId'],
                    Offer::TOTAL, Offer::UNDER, Offer::FULL_TIME
                );
            }

            $updates[] = new Update(
                $item['id'],
                $result,
                new LineCollection(...$lines),
                new OfferCollection(...$lineOffers)
            );
        }

        return new UpdateCollection(self::PROVIDER_NAME, ...$updates);
    }
}
