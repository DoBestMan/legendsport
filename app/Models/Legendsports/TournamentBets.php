<?php

namespace App\Models\Legendsports;

use Illuminate\Database\Eloquent\Model;

class TournamentBets extends Model
{
    protected $table = 'tournaments_bets';
    protected $primaryKey = 'id';

    public function tournamentPlayer()
    {
        return $this->belongsTo('App\Models\Legendsports\TournamentPlayer');
    }

    public function tournamentBetsEvents()
    {
        return $this->hasMany('App\Models\Legendsports\TournamentBetsEvents');
    }
}
