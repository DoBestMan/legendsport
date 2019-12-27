<?php

namespace App\Models\Backstage;

use Illuminate\Database\Eloquent\Model;

class TournamentBets extends Model
{
    protected $table = 'tournaments_bets';
    protected $primaryKey = 'id';

    public function player()
    {
        return $this->belongsTo('App\Models\Backstage\TournamentPlayers');
    }

    public function betsevents()
    {
        return $this->hasMany('App\Models\Backstage\TournamentBetsEvents');
    }
}
