<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $tournament_id
 * @property int $user_id
 * @property int $commission
 * @property int $chips
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Tournament $tournament
 * @property-read User $user
 * @property-read Collection|TournamentBet[] $bets
 */
class TournamentPlayer extends Model
{
    protected $table = 'tournaments_players';
    protected $primaryKey = 'id';

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
}
