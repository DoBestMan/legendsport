<?php
namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class DecimalCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return as_decimal($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        $decimal = as_decimal($value);
        return $decimal ? $decimal->toString() : null;
    }
}
