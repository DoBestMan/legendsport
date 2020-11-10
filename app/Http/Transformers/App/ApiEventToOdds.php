<?php

namespace App\Http\Transformers\App;

use App\Domain\ApiEvent;
use App\Domain\ApiEventOdds;
use League\Fractal\TransformerAbstract;

class ApiEventToOdds extends TransformerAbstract
{
    public function transform(ApiEvent $apiEvent)
    {
        /** @var ApiEventOdds[] $odds */
        $odds = $apiEvent->getAllOdds();

        return [
            "external_id" => $apiEvent->getApiId(),
            "moneyline_away" => isset($odds['moneyline_away']) ? $odds['moneyline_away']->getOdds() : null,
            "moneyline_home" => isset($odds['moneyline_home']) ? $odds['moneyline_home']->getOdds() : null,
            "point_spread_away" => isset($odds['spread_away']) ? $odds['spread_away']->getOdds() : null,
            "point_spread_home" => isset($odds['spread_home']) ? $odds['spread_home']->getOdds() : null,
            "point_spread_home_line" => isset($odds['spread_home']) ? $odds['spread_home']->getHandicap() : null,
            "point_spread_away_line" => isset($odds['spread_away']) ? $odds['spread_away']->getHandicap() : null,
            "overline" => isset($odds['total_over']) ? $odds['total_over']->getOdds() : null,
            "underline" => isset($odds['total_under']) ? $odds['total_under']->getOdds() : null,
            "total_number" => isset($odds['total_over']) ? $odds['total_over']->getHandicap() : null,
        ];
    }
}
