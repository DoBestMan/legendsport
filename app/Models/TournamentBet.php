<?php

namespace App\Models;

use App\Tournament\BetStatus;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $tournament_id
 * @property int $tournament_player_id
 * @property int $chips_wager
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Tournament $tournament
 * @property-read TournamentPlayer $player
 * @property-read Collection|TournamentBetEvent[] $betEvents
 * @mixin Eloquent
 */
class TournamentBet extends Model
{
    use StaticTable;

    protected $table = 'tournament_bets';
    protected $casts = [
        "chips_wager" => "integer",
    ];

    public function player()
    {
        return $this->belongsTo(TournamentPlayer::class);
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function betEvents()
    {
        return $this->hasMany(TournamentBetEvent::class);
    }

    public function getStatus() : BetStatus
    {
        $betEventStatuses = $this->betEvents->map(
            fn(TournamentBetEvent $betEvent) => $betEvent->status
        );

        if (
            $betEventStatuses->some(
                fn (BetStatus $betStatus) => $betStatus->equals(BetStatus::LOSS())
            )
        ) {
            return BetStatus::LOSS();
        }

        if (
            $betEventStatuses->some(
                fn (BetStatus $betStatus) => $betStatus->equals(BetStatus::PENDING())
            )
        ) {
            return BetStatus::PENDING();
        }

        return BetStatus::WIN();
    }

    public function getChipsWin() : int
    {
        return intval($this->chips_wager * $this->getMultiplier() - $this->chips_wager);
    }

    public function getMultiplier() : float
    {
        return $this->betEvents->map(fn(TournamentBetEvent $betEvent) => 1 + american_to_decimal($betEvent->odd))
            ->reduce(fn($a, $b) => $a * $b, 1);
    }
}
