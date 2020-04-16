<?php
namespace App\Casts;

use App\Tournament\Enums\PlayersLimit;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class PlayersLimitCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return new PlayersLimit($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return (new PlayersLimit($value))->getValue();
    }
}
