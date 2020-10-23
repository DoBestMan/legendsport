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

    // If odd equals 1 lets make it a null. Otherwise we would have to
    // return -Inf which is impossible to display. What is more
    // it doesn't make any sense.
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

if (!function_exists('str_ordinal')) {
    /**
     * Append an ordinal indicator to a numeric value.
     *
     * @param  string|int $value
     * @param  bool $superscript
     * @return string
     */
    function str_ordinal($value, $superscript = false)
    {
        $number = abs($value);
        $indicators = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];

        $suffix = $superscript ? '<sup>' . $indicators[$number % 10] . '</sup>' : $indicators[$number % 10];
        if ($number % 100 >= 11 && $number % 100 <= 13) {
            $suffix = $superscript ? '<sup>th</sup>' : 'th';
        }

        return number_format($number) . $suffix;
    }
}
