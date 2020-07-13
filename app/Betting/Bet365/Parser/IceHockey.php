<?php

namespace App\Betting\Bet365\Parser;

class IceHockey extends AbstractParser
{
    protected string $moneyLineTeamField = 'name';
    protected string $spreadTeamField = 'name';
    protected string $totalsTypeField = 'name';
    protected array $moneyLinePath = ['main', 'sp', 'money_line_3_way'];
    protected array $spreadPath = ['main', 'sp', 'puck_line'];
    protected array $totalsPath = ['main', 'sp', 'game_totals'];
}
