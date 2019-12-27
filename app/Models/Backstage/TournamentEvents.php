<?php

namespace App\Models\Backstage;

use Illuminate\Database\Eloquent\Model;

class TournamentEvents extends Model
{
    protected $table = 'tournaments_events';
    protected $primaryKey = 'id';

    public function tournaments()
    {
        return $this->belongsTo('App\Models\Backstage\Tournaments');
    }

    public function apiEvents()
    {
        return $this->belongsTo('App\Models\Backstage\ApiEvents');
    }

    public function betsevents()
    {
        return $this->hasMany('App\Models\Backstage\TournamentBetsEvents');
    }
}
