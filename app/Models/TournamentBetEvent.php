<?php

namespace App\Models;

use App\Casts\BetStatusCast;
use App\Casts\DecimalCast;
use App\Casts\PendingOddTypeCast;
use App\Tournament\Enums\BetStatus;
use App\Tournament\Enums\PendingOddType;
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
        "handicap" => DecimalCast::class,
        "odd" => "int",
        "status" => BetStatusCast::class,
        "type" => PendingOddTypeCast::class,
    ];

    public function tournamentBet()
    {
        return $this->belongsTo(TournamentBet::class);
    }

    public function tournamentEvent()
    {
        return $this->belongsTo(TournamentEvent::class);
    }

    public function getSelectedTeam(): ?string
    {
        switch ($this->type) {
            case PendingOddType::MONEY_LINE_HOME():
            case PendingOddType::SPREAD_HOME():
                return $this->tournamentEvent->apiEvent->team_home;

            case PendingOddType::MONEY_LINE_AWAY():
            case PendingOddType::SPREAD_AWAY():
                return $this->tournamentEvent->apiEvent->team_away;

            default:
                return null;
        }
    }
}
