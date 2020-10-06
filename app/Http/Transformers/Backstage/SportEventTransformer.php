<?php
namespace App\Http\Transformers\Backstage;

use App\Betting\SportEvent;
use League\Fractal\TransformerAbstract;

class SportEventTransformer extends TransformerAbstract
{
    public function transform(SportEvent $sportEvent)
    {
        return [
            "external_id" => $sportEvent->getExternalId(),
            "provider" => $sportEvent->getProvider(),
            "sport_id" => $sportEvent->getSportId(),
            "starts_at" => format_datetime($sportEvent->getStartsAt()),
            "team_away" => $sportEvent->getAwayTeam(),
            "team_home" => $sportEvent->getHomeTeam(),
            "pitcher_away" => $sportEvent->getAwayPitcher(),
            "pitcher_home" => $sportEvent->getHomePitcher(),
        ];
    }
}
