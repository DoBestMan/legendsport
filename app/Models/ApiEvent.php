<?php

namespace App\Models;

use App\Betting\SportEvent;
use Arr;
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

    public function getApiDataAttribute(string $encoded): SportEvent
    {
        $data = json_decode($encoded, true);

        return new SportEvent(
            $this->id,
            $data["external_id"],
            $data["starts_at"],
            $data["sport_id"],
            $data["home_team"],
            $data["away_team"],
            Arr::get($data, "provider"),
        );
    }

    public function setApiDataAttribute(array $sportEvent): void
    {
        unset($sportEvent["id"]);
        $this->attributes["api_data"] = json_encode($sportEvent);
    }

    public function tournamentEvents()
    {
        return $this->hasMany(TournamentEvent::class);
    }
}
