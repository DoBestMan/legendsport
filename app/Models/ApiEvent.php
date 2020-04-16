<?php

namespace App\Models;

use App\Betting\TimeStatus;
use App\Casts\TimeStatusCast;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
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
 * @method static ApiEvent notFinished()
 * @method static ApiEvent started()
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
        "time_status" => TimeStatusCast::class,
    ];

    public static function findByApiId(string $apiId): ?ApiEvent
    {
        return static::where("api_id", $apiId)->first();
    }

    public function tournamentEvents()
    {
        return $this->hasMany(TournamentEvent::class);
    }

    public function scopeNotFinished(Builder $query)
    {
        return $query->whereNotIn("time_status", [TimeStatus::CANCELED(), TimeStatus::ENDED()]);
    }

    public function scopeStarted(Builder $query)
    {
        return $query->where("starts_at", "<=", Carbon::now()->subMinutes(5));
    }

    public function isFinished(): bool
    {
        return $this->time_status->equals(TimeStatus::ENDED()) ||
            $this->time_status->equals(TimeStatus::CANCELED());
    }
}
