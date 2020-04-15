<?php

use Carbon\Carbon;
use Decimal\Decimal;

function error($input, $errors)
{
    return $errors->has($input) ? 'is-invalid' : '';
}

function as_decimal($value): ?Decimal
{
    if ($value === "" || $value === null) {
        return null;
    }

    return new Decimal($value);
}

function american_to_decimal(int $odd): Decimal
{
    if ($odd < 0) {
        return 100 / new Decimal(-$odd);
    }

    return new Decimal($odd) / 100;
}

function decimal_to_american($odd): ?int
{
    if (!$odd) {
        return null;
    }

    $odd = new Decimal($odd);

    if ($odd >= 2) {
        /** @var Decimal $result */
        $result = ($odd - 1) * 100;
        return $result->round()->toInt();
    }

    if ($odd == 1) {
        return null;
    }

    /** @var Decimal $result */
    $result = -100 / ($odd - 1);
    return $result->round()->toInt();
}

function format_datetime(?Carbon $date): ?string
{
    return $date ? $date->toAtomString() : null;
}
