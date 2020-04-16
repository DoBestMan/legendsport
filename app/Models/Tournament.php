<?php

namespace App\Models;

use App\Casts\PlayersLimitCast;
use App\Casts\TimeFrameCast;
use App\Casts\TournamentStateCast;
use App\Tournament\Enums\PlayersLimit;
use App\Tournament\Enums\TimeFrame;
use App\Tournament\Enums\TournamentState;
use App\Tournament\TournamentPrizeStructure;
use Carbon\Carbon;
use Eloquent;
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
 * @property TournamentState $state
 * @property TimeFrame $time_frame
 * @property array $late_register_rule
 * @property array $prize_pool
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection|TournamentPlayer[] $players
 * @property-read Collection|TournamentEvent[] $events
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

    public function getPrizes(): array
    {
        $prizeStructure = new TournamentPrizeStructure(
            $this->getPrizePoolMoney(),
            $this->getPlayersCount(),
        );
        return $prizeStructure->getPrizes();
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
