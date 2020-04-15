<?php

namespace App\Models;

use App\Casts\DecimalCast;
use App\Tournament\BetStatus;
use App\Tournament\PendingOddType;
use Carbon\Carbon;
use Decimal\Decimal;
use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $tournament_bet_id
 * @property int $tournament_event_id
 * @property int $odd
 * @property Decimal|null $handicap
 * @property BetStatus $status
 * @property PendingOddType $type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read TournamentBet $tournamentBet
 * @property-read TournamentEvent $tournamentEvent
 * @mixin Eloquent
 */
class TournamentBetEvent extends Model
{
    use StaticTable;

    protected $table = 'tournament_bet_events';
    protected $casts = [
        "odd" => "int",
        "handicap" => DecimalCast::class,
    ];

    public function getStatusAttribute(string $value): BetStatus
    {
        return new BetStatus($value);
    }

    public function setStatusAttribute(BetStatus $betStatus): void
    {
        $this->attributes["status"] = $betStatus->getValue();
    }

    public function getTypeAttribute(string $value): PendingOddType
    {
        return new PendingOddType($value);
    }

    public function setTypeAttribute(PendingOddType $pendingOddType): void
    {
        $this->attributes["type"] = $pendingOddType->getValue();
    }

    public function tournamentBet()
    {
        return $this->belongsTo(TournamentBet::class);
    }

    public function tournamentEvent()
    {
        return $this->belongsTo(TournamentEvent::class);
    }

    public function getSelectedTeam(): string
    {
        switch ($this->type) {
            case PendingOddType::MONEY_LINE_HOME():
            case PendingOddType::SPREAD_HOME():
                return $this->tournamentEvent->apiEvent->team_home;

            case PendingOddType::MONEY_LINE_AWAY():
            case PendingOddType::SPREAD_AWAY():
                return $this->tournamentEvent->apiEvent->team_away;

            default:
                return "n/a";
        }
    }

    public function markAsWin()
    {
        $this->status = BetStatus::WIN();
        $this->save();
    }

    public function markAsLost()
    {
        $this->status = BetStatus::LOSS();
        $this->save();
    }

    public function markAsPush()
    {
        $this->status = BetStatus::PUSH();
        $this->save();
    }
}
