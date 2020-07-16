<?php

namespace App\Betting\Bet365\Parser;

class AmericanFootball extends AbstractParser
{
    protected string $moneyLineTeamField = 'name';
    protected string $spreadTeamField = 'name';
    protected string $totalsTypeField = 'name';
    protected array $moneyLinePath = ['main', 'sp', 'money_line'];
    protected array $spreadPath = ['main', 'sp', 'point_spread'];
    protected array $totalsPath = ['main', 'sp', 'game_totals'];
}
