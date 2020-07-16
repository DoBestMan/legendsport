<?php

namespace App\Betting\Bet365\Parser;

class Tennis extends AbstractParser
{
    protected string $moneyLineTeamField = 'name';
    protected string $spreadTeamField = 'header';
    protected string $totalsTypeField = 'header';
    protected array $moneyLinePath = ['main', 'sp', 'to_win_match'];
    protected array $spreadPath = ['main', 'sp', 'match_handicap_(games)'];
    protected array $totalsPath = ['main', 'sp', 'total_games_2_way'];
}
