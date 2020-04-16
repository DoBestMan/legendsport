<?php
namespace App\Casts;

use App\Tournament\Enums\PendingOddType;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class PendingOddTypeCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return new PendingOddType($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return (new PendingOddType($value))->getValue();
    }
}
