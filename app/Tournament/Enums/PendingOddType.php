<?php
namespace App\Tournament\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static PendingOddType MONEY_LINE_HOME()
 * @method static PendingOddType MONEY_LINE_AWAY()
 * @method static PendingOddType SPREAD_HOME()
 * @method static PendingOddType SPREAD_AWAY()
 * @method static PendingOddType TOTAL_UNDER()
 * @method static PendingOddType TOTAL_OVER()
 */
final class PendingOddType extends Enum
{
    private const MONEY_LINE_HOME = "moneyline_home";
    private const MONEY_LINE_AWAY = "moneyline_away";
    private const SPREAD_HOME = "spread_home";
    private const SPREAD_AWAY = "spread_away";
    private const TOTAL_UNDER = "total_under";
    private const TOTAL_OVER = "total_over";
}
