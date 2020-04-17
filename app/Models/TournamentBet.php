<?php

namespace App\Models;

use App\Tournament\BetStatusCalculator;
use App\Tournament\Enums\BetStatus;
use Carbon\Carbon;
use Decimal\Decimal;
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
 * @property-read int $chips_win
 * @property-read BetStatus $status
 * @property-read Tournament $tournament
 * @property-read TournamentPlayer $tournamentPlayer
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

    public function tournamentPlayer()
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

    public function getStatusAttribute(): BetStatus
    {
        $betEventStatuses = $this->betEvents->map(
            fn(TournamentBetEvent $betEvent) => $betEvent->status,
        );

        return (new BetStatusCalculator($betEventStatuses))->calculate();
    }

    public function getChipsWinAttribute(): int
    {
        return intval($this->chips_wager * $this->getReducedMultiplier() - $this->chips_wager);
    }

    /**
     * Get multiplier where PUSH bet events are ignored.
     *
     * @return Decimal
     */
    public function getReducedMultiplier(): Decimal
    {
        return $this->betEvents
            ->filter(fn(TournamentBetEvent $event) => !$event->status->equals(BetStatus::PUSH()))
            ->map(fn(TournamentBetEvent $event) => 1 + american_to_decimal($event->odd))
            ->reduce(fn($a, $b) => $a * $b, new Decimal(1));
    }

    /**
     * Have bet wagers been graded already
     *
     * @return bool
     */
    public function isGraded(): bool
    {
        return !$this->status->equals(BetStatus::PENDING());
    }

    /**
     * Whether the bet player should be awarded with chips
     *
     * @return bool
     */
    public function isAwardable(): bool
    {
        return $this->status->equals(BetStatus::WIN()) || $this->status->equals(BetStatus::PUSH());
    }
}
