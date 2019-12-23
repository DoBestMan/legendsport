<?php

namespace App\Models\Legendsports;

use Illuminate\Database\Eloquent\Model;

class ApiEvents extends Model
{
    protected $table = 'api_events';
    protected $primaryKey = 'id';

    public function tournamentEvents()
    {
        return $this->hasMany('App\Models\Legendsports\TournamentEvents');
    }
}