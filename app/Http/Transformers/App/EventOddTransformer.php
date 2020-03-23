<?php
namespace App\Http\Transformers\App;

use League\Fractal\TransformerAbstract;

class EventOddTransformer extends TransformerAbstract
{
    public function transform(array $odds)
    {
        return [
            "id" => $odds[0]["EventID"],
            "money_line_away" => $odds[0]["MoneyLineAway"],
            "money_line_home" => $odds[0]["MoneyLineHome"],
            "point_spread_away" => $odds[0]["PointSpreadAway"],
            "point_spread_home" => $odds[0]["PointSpreadHome"],
            "point_spread_away_line" => $odds[0]["PointSpreadAwayLine"],
            "point_spread_home_line" => $odds[0]["PointSpreadHomeLine"],
            "overline" => $odds[0]["OverLine"],
            "underline" => $odds[0]["UnderLine"],
            "total_number" => $odds[0]["TotalNumber"],
        ];
    }
}
