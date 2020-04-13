<?php
namespace App\Http\Transformers\App;

use App\Models\ApiEvent;
use League\Fractal\TransformerAbstract;

class SportEventTransformer extends TransformerAbstract
{
    public function transform(ApiEvent $apiEvent)
    {
        return [
            "external_id" => $apiEvent->api_id,
            "id" => $apiEvent->id,
            "provider" => $apiEvent->provider,
            "score_away" => $apiEvent->score_away,
            "score_home" => $apiEvent->score_home,
            "sport_id" => $apiEvent->sport_id,
            "starts_at" => format_datetime($apiEvent->starts_at),
            "team_away" => $apiEvent->team_away,
            "team_home" => $apiEvent->team_home,
        ];
    }
}
