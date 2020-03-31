<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $tournament_id
 * @property int $user_id
 * @property int $chips
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Tournament $tournament
 * @property-read User $user
 * @property-read Collection|TournamentBet[] $bets
 * @mixin Eloquent
 */
class TournamentPlayer extends Model
{
    use StaticTable;

    protected $table = 'tournament_players';
    protected $casts = [
        'chips' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function bets()
    {
        return $this->hasMany(TournamentBet::class);
    }

    /**
     * The BALANCE is the amount of available
     * and pending chips bet for an event that is still in RUNNING status
     *
     * @return int
     */
    public function getBalance(): int
    {
        return $this->chips +
            $this->bets->sum(fn(TournamentBet $tournamentBet) => $tournamentBet->chips_wager);
    }
}
