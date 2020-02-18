<?php

namespace App\Models\Backstage;

use Illuminate\Database\Eloquent\Model;

class TournamentBets extends Model
{
    protected $table = 'tournaments_bets';
    protected $primaryKey = 'id';

    public function players()
    {
        return $this->belongsTo('App\Models\Backstage\TournamentPlayers');
    }

    public function betsEvents()
    {
        return $this->hasMany('App\Models\Backstage\TournamentBetsEvents');
    }
}
