<?php

namespace App\Models;

use App\Tournament\TournamentPrizeStructure;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use UnexpectedValueException;

// TODO Create tournament state enum

/**
 * @property int $id
 * @property string $name
 * @property string $players_limit
 * @property int $buy_in
 * @property int $chips
 * @property int $commission
 * @property bool $late_register
 * @property string $state
 * @property string $time_frame
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

    protected $table = 'tournaments';
    protected $casts = [
        'buy_in' => 'integer',
        'chips' => 'integer',
        'commission' => 'integer',
        'late_register_rule' => 'json',
        'prize_pool' => 'json',
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
