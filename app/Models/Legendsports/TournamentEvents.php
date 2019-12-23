<?php

namespace App\Models\Legendsports;

use Illuminate\Database\Eloquent\Model;

class TournamentEvents extends Model
{
    protected $table = 'tournaments_events';
    protected $primaryKey = 'id';

    public function tournaments()
    {
        return $this->belongsTo('App\Models\Legendsports\Tournaments');
    }

    public function apiEvents()
    {
        return $this->belongsTo('App\Models\Legendsports\ApiEvents');
    }

    public function tournamentBetsEvents()
    {
        return $this->hasMany('App\Models\Legendsports\TournamentBetsEvents');
    }
}
