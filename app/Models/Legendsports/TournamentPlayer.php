<?php

namespace App\Models\Legendsports;

use Illuminate\Database\Eloquent\Model;

class TournamentPlayer extends Model
{
    protected $table = 'tournaments_players';
    protected $primaryKey = 'id';

    public function users()
    {
        return $this->belongsTo('App\Models\Legendsports\Users');
    }

    public function config()
    {
        return $this->belongsTo('App\Models\Legendsports\Config');
    }

    public function tournaments()
    {
        return $this->belongsTo('App\Models\Legendsports\Tournaments');
    }

    public function tournamentBets()
    {
        return $this->hasMany('App\Models\Legendsports\TournamentBets');
    }
}
