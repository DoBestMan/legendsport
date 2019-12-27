<?php

namespace App\Models\Backstage;

use Illuminate\Database\Eloquent\Model;

class TournamentPlayers extends Model
{
    protected $table = 'tournaments_players';
    protected $primaryKey = 'id';

    public function users()
    {
        return $this->belongsTo('App\Models\Backstage\Users');
    }

    public function tournaments()
    {
        return $this->belongsTo('App\Models\Backstage\Tournaments');
    }

    public function bets()
    {
        return $this->hasMany('App\Models\Backstage\TournamentBets');
    }
}
