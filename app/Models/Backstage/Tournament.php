<?php

namespace App\Models\Backstage;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int                        $id
 * @property string                     $name
 * @property string                     $players_limit
 * @property int                        $buy_in
 * @property int                        $chips
 * @property int                        $commission
 * @property bool                       $late_register
 * @property string                     $state
 * @property string                     $time_frame
 * @property array                      $late_register_rule
 * @property array                      $prize_pool
 * @property array                      $prizes
 * @property Carbon                     $created_at
 * @property Carbon                     $updated_at
 * @property-read Collection|TournamentPlayer[] $players
 * @property-read Collection|TournamentEvent[] $events
 * @property-read Collection|TournamentSport[] $sports
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

    public function sports()
    {
        return $this->hasMany(TournamentSport::class);
    }

    public function events()
    {
        return $this->hasMany(TournamentEvent::class);
    }
}
