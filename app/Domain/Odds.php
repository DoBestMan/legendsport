<?php

namespace App\Domain;

use Decimal\Decimal;

class Odds
{
    public static function americanToDecimalWinnings(int $odd): Decimal
    {
        if ($odd < 0) {
            return 100 / new Decimal(-$odd);
        }

        return new Decimal($odd) / 100;
    }

    public static function decimalToAmerican($odd): ?int
    {
        if (!$odd) {
            return null;
        }

        $odd = new Decimal((string) $odd);

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
}
