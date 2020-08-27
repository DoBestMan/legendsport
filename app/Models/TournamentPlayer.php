<?php

namespace App\Models;

use App\Tournament\Enums\BetStatus;
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
 * @property int $balance
 * @property-read int $pending_chips
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
        'balance' => 'integer',
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

    public function getPendingChipsAttribute(): int
    {
        return $this->bets
            ->filter(fn(TournamentBet $bet) => $bet->status->equals(BetStatus::PENDING()))
            ->sum(fn(TournamentBet $bet) => $bet->chips_wager);
    }

}
