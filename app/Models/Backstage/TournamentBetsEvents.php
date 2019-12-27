<?php

namespace App\Models\Backstage;

use Illuminate\Database\Eloquent\Model;

class TournamentBetsEvents extends Model
{
    protected $table = 'tournaments_bets_events';
    protected $primaryKey = 'id';

    public function Bets()
    {
        return $this->belongsTo('App\Models\Backstage\TournamentBets');
    }

    public function Events()
    {
        return $this->belongsTo('App\Models\Backstage\TournamentEvents');
    }
}
