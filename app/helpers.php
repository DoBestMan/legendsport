<?php

use Carbon\Carbon;
use Decimal\Decimal;

function error($input, $errors)
{
    return $errors->has($input) ? 'is-invalid' : '';
}

function american_to_decimal(int $odd): Decimal
{
    if ($odd < 0) {
        return 100 / new Decimal(-$odd);
    }

    return new Decimal($odd) / 100;
}

function format_datetime(?Carbon $date): ?string
{
    return $date ? $date->toAtomString() : null;
}
