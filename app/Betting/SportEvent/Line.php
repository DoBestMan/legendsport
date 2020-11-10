<?php

namespace App\Betting\SportEvent;

use App\Betting\Settlement;
use Decimal\Decimal;

class Line
{
    private string $id;
    private ?int $price;
    private ?Decimal $line;
    private ?Settlement $settlement;

    public function __construct(string $id, ?int $price, ?Decimal $line, ?Settlement $settlement)
    {
        $this->id = $id;
        $this->price = $price;
        $this->line = $line;
        $this->settlement = $settlement;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function getLine(): ?Decimal
    {
        return $this->line;
    }

    public function getSettlement(): ?Settlement
    {
        return $this->settlement;
    }
}
