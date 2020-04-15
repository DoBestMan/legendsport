<?php

namespace App\Models;

use App\Betting\TimeStatus;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $api_id
 * @property TimeStatus $time_status
 * @property Carbon|null $starts_at
 * @property string|null $sport_id
 * @property string $team_away
 * @property string $team_home
 * @property int|null $score_away
 * @property int|null $score_home
 * @property string $provider
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection|TournamentEvent[] $tournamentEvents
 * @mixin Eloquent
 */
class ApiEvent extends Model
{
    protected $table = 'api_events';
    protected $casts = [
        "score_away" => "int",
        "score_home" => "int",
        "sport_id" => "string",
        "starts_at" => "datetime",
    ];

    public static function findByApiId(string $apiId): ?ApiEvent
    {
        return static::where("api_id", $apiId)->first();
    }

    public function setTimeStatusAttribute(TimeStatus $timeStatus): void
    {
        $this->attributes["time_status"] = $timeStatus->getValue();
    }

    public function getTimeStatusAttribute(string $value): TimeStatus
    {
        return new TimeStatus($value);
    }

    public function tournamentEvents()
    {
        return $this->hasMany(TournamentEvent::class);
    }

    public function isFinished(): bool
    {
        return $this->time_status->equals(TimeStatus::ENDED()) ||
            $this->time_status->equals(TimeStatus::CANCELED());
    }
}
