<?php
namespace App\Casts;

use App\Tournament\Enums\BetStatus;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class BetStatusCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return new BetStatus($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return (new BetStatus($value))->getValue();
    }
}
