<?php
namespace App\Http\Transformers\App;

use App\Betting\SportEventOdd;
use League\Fractal\TransformerAbstract;

class SportEventOddTransformer extends TransformerAbstract
{
    public function transform(SportEventOdd $sportEventOdd)
    {
        return [
            "external_id" => $sportEventOdd->getExternalEventId(),
            "money_line_away" => $sportEventOdd->getMoneyLineAway(),
            "money_line_home" => $sportEventOdd->getMoneyLineHome(),
            "point_spread_away" => $sportEventOdd->getPointSpreadAway(),
            "point_spread_home" => $sportEventOdd->getPointSpreadHome(),
            "point_spread_away_line" => $sportEventOdd->getPointSpreadAwayLine(),
            "point_spread_home_line" => $sportEventOdd->getPointSpreadHomeLine(),
            "overline" => $sportEventOdd->getOverLine(),
            "underline" => $sportEventOdd->getUnderLine(),
            "total_number" => $sportEventOdd->getTotalNumber(),
        ];
    }
}
