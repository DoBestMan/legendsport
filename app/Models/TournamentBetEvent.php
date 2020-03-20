<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $tournament_bet_id
 * @property int $tournament_event_id
 * @property array $bet
 * @property string $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read TournamentEvent $event
 */
class TournamentBetEvent extends Model
{
    protected $table = 'tournaments_bets_events';
    protected $primaryKey = 'id';
    protected $casts = [
        'bet' => 'json',
    ];

    public function bet()
    {
        return $this->belongsTo(TournamentBet::class);
    }

    public function event()
    {
        return $this->belongsTo(TournamentEvent::class);
    }
}
