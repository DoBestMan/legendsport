<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property int $id
 * @property string $name
 * @property int $balance
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection|TournamentBet[] $bets
 * @property-read Collection|TournamentPlayer[] $players
 * @property-read Collection|Tournament[] $tournaments
 */
class User extends Authenticatable
{
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'balance' => 'integer',
        'email_verified_at' => 'datetime',
    ];

    public function players()
    {
        return $this->hasMany(TournamentPlayer::class);
    }

    public function tournaments()
    {
        return $this->hasManyThrough(Tournament::class, TournamentPlayer::class);
    }

    public function bets()
    {
        return $this->hasManyThrough(TournamentBet::class, TournamentPlayer::class);
    }
}
