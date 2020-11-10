<?php

namespace App\Betting;

use App\Betting\SportEvent\Event;
use App\Betting\SportEvent\Line;
use App\Betting\SportEvent\LineCollection;
use App\Betting\SportEvent\Offer;
use App\Betting\SportEvent\OfferCollection;
use App\Betting\SportEvent\Result;
use App\Betting\SportEvent\Sport;
use App\Betting\SportEvent\Update;
use App\Betting\SportEvent\UpdateCollection;
use App\Models\ApiEvent;
use App\Tournament\Enums\TournamentState;
use Carbon\Carbon;
use Decimal\Decimal;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Collection;

class TestData implements BettingProvider
{
    public const PROVIDER_NAME = "testdata";
    public const PROVIDER_DESCRIPTION = 'Test data';
    private EntityManager $entityManager;
    private array $tags = [
        [Offer::MONEYLINE, Offer::HOME, Offer::FULL_TIME],
        [Offer::MONEYLINE, Offer::AWAY, Offer::FULL_TIME],
        [Offer::SPREAD, Offer::HOME, Offer::FULL_TIME],
        [Offer::SPREAD, Offer::AWAY, Offer::FULL_TIME],
        [Offer::TOTAL, Offer::OVER, Offer::FULL_TIME],
        [Offer::TOTAL, Offer::UNDER, Offer::FULL_TIME],
    ];

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getEvents(int $page): Pagination
    {
        $page--;
        $perPage = 25;
        $his = explode(',', (new \DateTime())->format('h,i,s'));
        $startId =  + intval(($his[0] * 60 * 60 + $his[1] * 60 + $his[2]) / 15);
        srand(0);

        $results = collect(range(($page * $perPage) + $startId, (($page + 1) * $perPage) + $startId, 1))
            ->map(fn (int $id) => new Event(
                $id,
                (new \DateTime())->add(new \DateInterval('PT' . ($id - $startId) * 15 . 'S')),
                rand(1, 4) * 1000,
                'Home team ' . $id,
                'Away team ' . $id,
                static::PROVIDER_NAME,
                null,
                null
            )
        )
        ->all();

        return new Pagination($results, 5760 - $startId, $perPage);
    }

    public function getSports(): array
    {
        return [
            new Sport(1000, 'Laser Tag', self::PROVIDER_NAME),
            new Sport(2000, 'Air Hockey',self::PROVIDER_NAME),
            new Sport(3000, 'VR Dodgeball', self::PROVIDER_NAME),
            new Sport(4000, 'Jousting', self::PROVIDER_NAME),
        ];
    }

    public function getUpdates(): UpdateCollection
    {
        /** @var \App\Domain\ApiEvent[]|Collection $apiEvents */
        $qb = $this->entityManager->createQueryBuilder();
        $apiEvents = $qb->select('a')
            ->from(\App\Domain\ApiEvent::class, 'a')
            ->where($qb->expr()->eq('a.provider', '?1'))
            ->andWhere($qb->expr()->notIn('a.timeStatus', '?2'))
            ->getQuery()
            ->execute([1 => static::PROVIDER_NAME, 2 => [TimeStatus::ENDED(), TimeStatus::CANCELED()]]);

        $updates = [];

        foreach ($apiEvents as $apiEvent) {
            srand($apiEvent->getApiId());
            $result = $this->generateResult($apiEvent);

            $total = new Decimal((string) (((int) rand(1, 5)) + .5));
            $spreadHome = new Decimal((string) (((int) rand(-3, 3)) + .5));
            $spreadAway = new Decimal((string) (((int) rand(-3, 3)) + .5));

            $lines = [];
            $lineOffers = [];
            $settlements = [];

            if ($result->getTimeStatus()->equals(TimeStatus::ENDED())) {
                $settlements = $this->calculateSettlements($result, $total, $spreadHome, $spreadAway);
            }

            foreach ($this->tags as $tagset) {

                $line = null;
                switch (true) {
                    case in_array(Offer::TOTAL, $tagset):
                        $line = $total;
                        break;
                    case in_array(Offer::SPREAD, $tagset) && in_array(Offer::HOME, $tagset):
                        $line = $spreadHome;
                        break;
                    case in_array(Offer::SPREAD, $tagset) && in_array(Offer::AWAY, $tagset):
                        $line = $spreadAway;
                        break;
                }

                $lineId = implode('::', $tagset);
                $lines[] = new Line(
                    $lineId,
                    rand(-200, 500),
                    $line,
                    $settlements[$lineId] ?? null
                );

                $lineOffers[] = new Offer($lineId, ...$tagset);
            }

            $updates[] = new Update(
                $apiEvent->getApiId(),
                $result,
                new LineCollection(...$lines),
                new OfferCollection(...$lineOffers)
            );
        }

        return new UpdateCollection(self::PROVIDER_NAME, ...$updates);
    }

    private function generateResult(\App\Domain\ApiEvent $apiEvent): Result
    {
        $timeStatus = TimeStatus::NOT_STARTED();
        $home = $away = null;
        $finalHome = (int) rand(0, 5);
        $finalAway = (int) rand(0, 5);

        if (Carbon::now() >= $apiEvent->getStartsAt()) {
            $timeStatus = TimeStatus::IN_PLAY();
            $home = (int) $finalHome / 2;
            $away = (int) $finalAway / 2;
        }

        if (Carbon::now()->subMinutes(10) >= $apiEvent->getStartsAt()) {
            $timeStatus = TimeStatus::ENDED();
            $home = $finalHome;
            $away = $finalAway;
        }

        return new Result(
            $apiEvent->getApiId(),
            static::PROVIDER_NAME,
            $timeStatus,
            (new Carbon($apiEvent->getStartsAt()))->toString(),
            $home,
            $away,
            null,
            null
        );
    }

    private function calculateSettlements(Result $result, Decimal $total, Decimal $spreadHome, Decimal $spreadAway): array
    {
        $settlements = [];

        if ($result->getHome() > $result->getAway()) {
            $settlements[implode('::', $this->tags[0])] = Settlement::WON();
            $settlements[implode('::', $this->tags[1])] = Settlement::LOST();
        } elseif ($result->getHome() < $result->getAway()) {
            $settlements[implode('::', $this->tags[0])] = Settlement::LOST();
            $settlements[implode('::', $this->tags[1])] = Settlement::WON();
        } else {
            $settlements[implode('::', $this->tags[0])] = Settlement::PUSH();
            $settlements[implode('::', $this->tags[1])] = Settlement::PUSH();
        }

        $spreadAwayResult = $result->getAway() + $spreadAway - $result->getHome();
        if ($spreadAwayResult > 0) {
            $settlements[implode('::', $this->tags[3])] = Settlement::WON();
        } elseif ($spreadAwayResult < 0) {
            $settlements[implode('::', $this->tags[3])] = Settlement::LOST();
        } else {
            $settlements[implode('::', $this->tags[3])] = Settlement::PUSH();
        }

        $spreadHomeResult = $result->getHome() + $spreadHome - $result->getAway();
        if ($spreadHomeResult > 0) {
            $settlements[implode('::', $this->tags[2])] = Settlement::WON();
        } elseif ($spreadHomeResult < 0) {
            $settlements[implode('::', $this->tags[2])] = Settlement::LOST();
        } else {
            $settlements[implode('::', $this->tags[2])] = Settlement::PUSH();
        }

        $totalResult = $result->getHome() + $result->getAway();
        if ($totalResult > $total) {
            $settlements[implode('::', $this->tags[4])] = Settlement::WON();
            $settlements[implode('::', $this->tags[5])] = Settlement::LOST();
        } elseif ($totalResult < $total) {
            $settlements[implode('::', $this->tags[4])] = Settlement::LOST();
            $settlements[implode('::', $this->tags[5])] = Settlement::WON();
        } else {
            $settlements[implode('::', $this->tags[4])] = Settlement::PUSH();
            $settlements[implode('::', $this->tags[5])] = Settlement::PUSH();
        }

        return $settlements;
    }
}
