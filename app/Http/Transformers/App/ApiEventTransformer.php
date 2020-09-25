<?php
namespace App\Http\Transformers\App;

use App\Models\ApiEvent;
use League\Fractal\TransformerAbstract;

class ApiEventTransformer extends TransformerAbstract
{
    public function transform(ApiEvent $apiEvent)
    {
        return [
            "external_id" => $apiEvent->api_id,
            "id" => $apiEvent->id,
            "status" => ucwords(str_replace('_', ' ', $apiEvent->time_status->getValue())),
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
