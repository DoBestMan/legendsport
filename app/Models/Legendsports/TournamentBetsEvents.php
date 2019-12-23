<?php

namespace App\Models\Legendsports;

use Illuminate\Database\Eloquent\Model;

class TournamentBetsEvents extends Model
{
    protected $table = 'tournaments_bets_events';
    protected $primaryKey = 'id';

    public function tournamentBets()
    {
        return $this->belongsTo('App\Models\Legendsports\TournamentBets');
    }

    public function tournamentEvents()
    {
        return $this->belongsTo('App\Models\Legendsports\TournamentEvents');
    }
}
