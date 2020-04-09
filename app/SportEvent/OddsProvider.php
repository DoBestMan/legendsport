<?php
namespace App\SportEvent;

interface OddsProvider
{
    /**
     * @return SportEventOdd[]
     */
    public function getOdds(): array;
}
