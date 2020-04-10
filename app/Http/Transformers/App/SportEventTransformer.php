<?php
namespace App\Http\Transformers\App;

use App\Betting\SportEvent;
use League\Fractal\TransformerAbstract;

class SportEventTransformer extends TransformerAbstract
{
    public function transform(SportEvent $sportEvent)
    {
        return [
            "id" => $sportEvent->getId(),
            "external_id" => $sportEvent->getExternalId(),
            "starts_at" => format_datetime($sportEvent->getStartsAt()),
            "sport_id" => $sportEvent->getSportId(),
            "home_team" => $sportEvent->getHomeTeam(),
            "away_team" => $sportEvent->getAwayTeam(),
            "provider" => $sportEvent->getProvider(),
        ];
    }
}