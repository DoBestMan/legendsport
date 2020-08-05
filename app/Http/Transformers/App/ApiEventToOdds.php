<?php

namespace App\Http\Transformers\App;

use App\Domain\ApiEvent;
use App\Domain\ApiEventOdds;
use App\Domain\BetTypes\MoneyLineAway;
use App\Domain\BetTypes\MoneyLineHome;
use App\Domain\BetTypes\SpreadAway;
use App\Domain\BetTypes\SpreadHome;
use App\Domain\BetTypes\TotalOver;
use App\Domain\BetTypes\TotalUnder;
use League\Fractal\TransformerAbstract;

class ApiEventToOdds extends TransformerAbstract
{
    public function transform(ApiEvent $apiEvent)
    {
        /** @var ApiEventOdds[] $odds */
        $odds = $apiEvent->getAllOdds();

        return [
            "external_id" => $apiEvent->getApiId(),
            "money_line_away" => isset($odds[MoneyLineAway::class]) ? $odds[MoneyLineAway::class]->getOdds() : null,
            "money_line_home" => isset($odds[MoneyLineHome::class]) ? $odds[MoneyLineHome::class]->getOdds() : null,
            "point_spread_away" => isset($odds[SpreadAway::class]) ? $odds[SpreadAway::class]->getOdds() : null,
            "point_spread_home" => isset($odds[SpreadHome::class]) ? $odds[SpreadHome::class]->getOdds() : null,
            "point_spread_away_line" => isset($odds[SpreadHome::class]) ? $odds[SpreadHome::class]->getHandicap() : null,
            "point_spread_home_line" => isset($odds[SpreadAway::class]) ? $odds[SpreadAway::class]->getHandicap() : null,
            "overline" => isset($odds[TotalOver::class]) ? $odds[TotalOver::class]->getOdds() : null,
            "underline" => isset($odds[TotalUnder::class]) ? $odds[TotalUnder::class]->getOdds() : null,
            "total_number" => isset($odds[TotalOver::class]) ? $odds[TotalOver::class]->getHandicap() : null,
        ];
    }
}
