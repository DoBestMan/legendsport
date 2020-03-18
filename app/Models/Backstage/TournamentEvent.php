<?php

namespace App\Models\Backstage;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int                        $id
 * @property int                        $tournament_id
 * @property int                        $api_event_id
 * @property Carbon                     $created_at
 * @property Carbon                     $updated_at
 * @property-read ApiEvent $apiEvent
 */
class TournamentEvent extends Model
{
    protected $table = 'tournaments_events';
    protected $primaryKey = 'id';
    protected $fillable = ['tournament_id', 'api_event_id'];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function apiEvent()
    {
        return $this->belongsTo(ApiEvent::class);
    }

    public function betsEvents()
    {
        return $this->hasMany(TournamentBetsEvent::class);
    }
}
