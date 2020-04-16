<?php
namespace App\Casts;

use App\Tournament\Enums\TimeFrame;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class TimeFrameCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return new TimeFrame($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return (new TimeFrame($value))->getValue();
    }
}
