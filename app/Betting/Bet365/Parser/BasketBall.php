<?php

namespace App\Betting\Bet365\Parser;

class BasketBall extends AbstractParser
{
    protected string $moneyLineTeamField = 'name';
    protected string $spreadTeamField = 'name';
    protected string $totalsTypeField = 'header';
    protected array $moneyLinePath = ['main', 'sp', 'money_line'];
    protected array $spreadPath = ['main', 'sp', 'point_spread'];
    protected array $totalsPath = ['main', 'sp', 'game_totals'];
}
