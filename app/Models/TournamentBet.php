<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $tournament_player_id
 * @property int $chips_wager
 * @property int $chips_win
 * @property string $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read TournamentPlayer $player
 * @property-read Collection|TournamentBetEvent[] betEvents
 */
class TournamentBet extends Model
{
    protected $table = 'tournaments_bets';
    protected $primaryKey = 'id';

    public function player()
    {
        return $this->belongsTo(TournamentPlayer::class);
    }

    public function betEvents()
    {
        return $this->hasMany(TournamentBetEvent::class);
    }
}
