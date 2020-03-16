<?php

namespace App\Models\Backstage;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int                        $id
 * @property Carbon                     $created_at
 * @property Carbon                     $updated_at
 * @property-read Collection|TournamentPlayer[] $players
 * @property-read Collection|TournamentEvent[] $events
 */
class Tournament extends Model
{
    protected $table = 'tournaments';
    protected $primaryKey = 'id';

    protected $casts = [
        'late_register_rule' => 'json',
        'prize_pool' => 'json',
        'prizes' => 'json',
    ];

    public function players()
    {
        return $this->hasMany(TournamentPlayer::class);
    }

    public function events()
    {
        return $this->hasMany(TournamentEvent::class);
    }
}
