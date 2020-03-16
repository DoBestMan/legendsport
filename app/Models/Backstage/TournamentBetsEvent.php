<?php

namespace App\Models\Backstage;

use Illuminate\Database\Eloquent\Model;

class TournamentBetsEvent extends Model
{
    protected $table = 'tournaments_bets_events';
    protected $primaryKey = 'id';

    public function Bets()
    {
        return $this->belongsTo(TournamentBet::class);
    }

    public function Events()
    {
        return $this->belongsTo(TournamentEvent::class);
    }
}
