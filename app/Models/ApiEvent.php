<?php

namespace App\Models;

use App\Betting\SportEvent;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $api_id
 * @property SportEvent $api_data
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection|TournamentEvent[] $tournamentEvents
 * @mixin Eloquent
 */
class ApiEvent extends Model
{
    protected $table = 'api_events';
    protected $primaryKey = 'id';
    protected $fillable = ['api_id', 'api_data'];
    protected $casts = [
        'api_data' => 'array', // TODO Maybe remove it
    ];

    public function getApiDataAttribute(array $data): SportEvent
    {
        return new SportEvent(
            $this->id,
            $data["external_id"],
            new Carbon($data["starts_at"]),
            $data["sport_id"],
            $data["home_team"],
            $data["away_team"],
        );
    }

    public function setApiDataAttribute(SportEvent $sportEvent): void
    {
        $this->attributes["api_data"] = [
            "external_id" => $sportEvent->getExternalId(),
            "starts_at" => $sportEvent->getStartsAt()->toAtomString(),
            "sport_id" => $sportEvent->getSportId(),
            "home_team" => $sportEvent->getHomeTeam(),
            "away_team" => $sportEvent->getAwayTeam(),
        ];
    }

    public function tournamentEvents()
    {
        return $this->hasMany(TournamentEvent::class);
    }
}
