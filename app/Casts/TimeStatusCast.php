<?php
namespace App\Casts;

use App\Betting\TimeStatus;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class TimeStatusCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return new TimeStatus($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return (new TimeStatus($value))->getValue();
    }
}
