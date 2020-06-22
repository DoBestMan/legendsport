<?php

namespace App\Betting;

use App\Models\ApiEvent;
use Carbon\Carbon;
use Decimal\Decimal;
use Illuminate\Support\Collection;

class TestData implements BettingProvider
{
    const PROVIDER_NAME = "testdata";

    public function getEvents(int $page): Pagination
    {
        $page--;
        $perPage = 25;
        $his = explode(',', (new \DateTime())->format('h,i,s'));
        $startId =  + intval(($his[0] * 60 * 60 + $his[1] * 60 + $his[2]) / 15);
        srand(0);

        $results = collect(range(($page * $perPage) + $startId, (($page + 1) * $perPage) + $startId, 1))
            ->map(fn (int $id) => new SportEvent(
                $id,
                (new \DateTime())->add(new \DateInterval('PT' . ($id - $startId) * 15 . 'S')),
                rand(1, 4),
                'Home team ' . $id,
                'Away team ' . $id,
                static::PROVIDER_NAME
            )
        )
        ->all();

        return new Pagination($results, 5760 - $startId, $perPage);
    }

    /**
     * @inheritDoc
     */
    public function getOdds(): array
    {
        /** @var ApiEvent[]|Collection $apiEventDict */
        $apiEventDict = ApiEvent::query()
            ->where("provider", static::PROVIDER_NAME)
            ->where("time_status", TimeStatus::NOT_STARTED())
            ->get();

        $odds = [];

        foreach ($apiEventDict as $item) {
            srand($item->api_id);
            $odds[] = new SportEventOdd(
                $item->api_id,
                rand(-200, 500),
                rand(-200, 500),
                rand(-200, 500),
                rand(-200, 500),
                new Decimal((string) (((int) rand(-3, 3)) + .5)),
                new Decimal((string) (((int) rand(-3, 3)) + .5)),
                rand(-200, 500),
                rand(-200, 500),
                new Decimal((string) (((int) rand(1, 5)) + .5))
            );
        }

        return $odds;
    }

    public function getResults(): array
    {
        /** @var ApiEvent[]|Collection $apiEvents */
        $apiEvents = ApiEvent::notFinished()
            ->started()
            ->where("provider", static::PROVIDER_NAME)
            ->get();

        $results = [];

        foreach ($apiEvents as $apiEvent) {
            $timeStatus = TimeStatus::NOT_STARTED();
            $home = $away = null;
            srand($apiEvent->api_id);
            $finalHome = (int) rand(0, 5);
            $finalAway = (int) rand(0, 5);

            if (Carbon::now() >= $apiEvent->starts_at) {
                $timeStatus = TimeStatus::IN_PLAY();
                $home = (int) $finalHome / 2;
                $away = (int) $finalAway / 2;
            }

            if (Carbon::now()->subMinutes(10) >= $apiEvent->starts_at) {
                $timeStatus = TimeStatus::ENDED();
                $home = $finalHome;
                $away = $finalAway;
            }

            $results[] = new SportEventResult(
                $apiEvent->api_id,
                $timeStatus,
                $home,
                $away
            );
        }

        return $results;
    }

    /**
     * @inheritDoc
     */
    public function getSports(): array
    {
        return [
            new Sport(1, 'Laser Tag'),
            new Sport(2, 'Air Hockey'),
            new Sport(3, 'VR Dodgeball'),
            new Sport(4, 'Jousting'),
        ];
    }
}
