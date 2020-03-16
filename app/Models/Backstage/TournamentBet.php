<?php

namespace App\Models\Backstage;

use Illuminate\Database\Eloquent\Model;

class TournamentBet extends Model
{
    protected $table = 'tournaments_bets';
    protected $primaryKey = 'id';

    public function players()
    {
        return $this->belongsTo(TournamentPlayer::class);
    }

    public function betsEvents()
    {
        return $this->hasMany(TournamentBetsEvent::class);
    }
}
