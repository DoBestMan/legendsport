<?php
namespace App\Tournament;

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
    private const MONEY_LINE_HOME = "money_line_home";
    private const MONEY_LINE_AWAY = "money_line_away";
    private const SPREAD_HOME = "spread_home";
    private const SPREAD_AWAY = "spread_away";
    private const TOTAL_UNDER = "total_under";
    private const TOTAL_OVER = "total_over";
}
