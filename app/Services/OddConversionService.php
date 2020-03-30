<?php
namespace App\Services;

class OddConversionService
{
    public function americanToDecimal(int $odd) : float
    {
        return ($odd < 0 ? 100 / -$odd : $odd / 100);
    }
}
