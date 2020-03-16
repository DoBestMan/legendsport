<?php

namespace App\Models\Backstage;

use Illuminate\Database\Eloquent\Model;

class TournamentPlayer extends Model
{
    protected $table = 'tournaments_players';
    protected $primaryKey = 'id';

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function tournaments()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function bets()
    {
        return $this->hasMany(TournamentBet::class);
    }
}
