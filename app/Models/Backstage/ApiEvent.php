<?php

namespace App\Models\Backstage;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int                        $id
 * @property string                     $api_id
 * @property array                      $api_data
 * @property Carbon                     $created_at
 * @property Carbon                     $updated_at
 * @property-read Collection|TournamentEvent[] $tournamentEvents
 */
class ApiEvent extends Model
{
    protected $table = 'api_events';
    protected $primaryKey = 'id';
    protected $fillable = ['api_id', 'api_data'];
    protected $casts = [
        'api_data' => 'array',
    ];

    public function tournamentEvents()
    {
        return $this->hasMany(TournamentEvent::class);
    }
}
