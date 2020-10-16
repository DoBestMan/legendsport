<?php

namespace App\Models;

use App\Casts\PlayersLimitCast;
use App\Casts\TimeFrameCast;
use App\Casts\TournamentStateCast;
use App\Domain\Prizes\PrizeStructure;
use App\Tournament\Enums\PlayersLimit;
use App\Tournament\Enums\TimeFrame;
use App\Tournament\Enums\TournamentState;
use App\Domain\Prizes\PrizeMoney;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use UnexpectedValueException;

/**
 * @property int $id
 * @property string $name
 * @property int $buy_in
 * @property int $chips
 * @property int $commission
 * @property bool $late_register
 * @property PlayersLimit $players_limit
 * @property Carbon $registration_deadline
 * @property Carbon $late_registration_deadline
 * @property TournamentState $state
 * @property Carbon $completed_at
 * @property TimeFrame $time_frame
 * @property array $late_register_rule
 * @property array $prize_pool
 * @property array $bots
 * @property int $bots_registered
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property bool $auto_end
 * @property bool $live_lines
 * @property-read Collection|TournamentPlayer[] $players
 * @property-read Collection|TournamentEvent[] $events
 * @method static Tournament active()
 * @mixin Eloquent
 */
class Tournament extends Model
{
    use StaticTable;

    // TODO Handle players limit

    protected $table = "tournaments";
    protected $casts = [
        "buy_in" => "integer",
        "chips" => "integer",
        "commission" => "integer",
        "late_register_rule" => "json",
        "bots" => "json",
        "players_limit" => PlayersLimitCast::class,
        "prize_pool" => "json",
        "state" => TournamentStateCast::class,
        "time_frame" => TimeFrameCast::class,
    ];

    public function players()
    {
        return $this->hasMany(TournamentPlayer::class);
    }

    public function events()
    {
        return $this->hasMany(TournamentEvent::class);
    }

    public function scopeActive(Builder $builder)
    {
        $builder->where("completed_at", ">", Carbon::now()->subDay(), 'or');
        $builder->whereNull("completed_at", 'or');
        return $builder->whereNotIn("state", [
            TournamentState::COMPLETED(),
            TournamentState::CANCELED(),
        ], 'or');
    }

    public function isFinished(): bool
    {
        return in_array($this->state, [TournamentState::COMPLETED(), TournamentState::CANCELED()]);
    }

    public function canBetBePlaced(): bool
    {
        return in_array($this->state, [
            TournamentState::REGISTERING(),
            TournamentState::LATE_REGISTERING(),
            TournamentState::RUNNING(),
        ]);
    }

    public function canUserRegister(): bool
    {
        return in_array($this->state, [
            TournamentState::REGISTERING(),
            TournamentState::LATE_REGISTERING(),
        ]);
    }

    /**
     * @return PrizeMoney[]
     */
    public function getPrizes(): array
    {
        $prizeStructure = PrizeStructure::getStructure($this->getPlayersCount());

        return $prizeStructure->toPrizeMoneyCollection($this->getPrizePoolMoney())->toArray();
    }

    public function getPrizePoolMoney(): int
    {
        $prizePoolType = $this->prize_pool["type"];

        switch ($prizePoolType) {
            case "Fixed":
                return $this->prize_pool["fixed_value"];
            case "Auto":
                return $this->getPlayersCount() * $this->buy_in;
            default:
                throw new UnexpectedValueException("Unexpected prize pool type");
        }
    }

    public function getPlayersCount(): int
    {
        return $this->players->count();
    }
}
