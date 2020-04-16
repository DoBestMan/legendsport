<?php
namespace App\Casts;

use App\Tournament\Enums\TournamentState;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class TournamentStateCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return new TournamentState($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return (new TournamentState($value))->getValue();
    }
}
