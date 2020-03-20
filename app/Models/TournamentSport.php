<?php

namespace App\Models\Backstage;

use App\Models\ApiEvent;
use App\Models\Tournament;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $sport_id
 * @property int $tournament_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read ApiEvent $apiEvent
 */
class TournamentSport extends Model
{
    protected $table = 'tournaments_sports';
    protected $primaryKey = 'id';
    protected $fillable = ['tournament_id', 'sport_id'];

    public function tournaments()
    {
        return $this->belongsTo(Tournament::class);
    }
}
