<?php
namespace App\Tournament\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static TimeFrame DAILY()
 * @method static TimeFrame WEEKLY()
 * @method static TimeFrame MONTHLY()
 * @method static TimeFrame SEASON_LONG()
 */
final class TimeFrame extends Enum
{
    private const DAILY = "Daily";
    private const WEEKLY = "Weekly";
    private const MONTHLY = "Monthly";
    private const SEASON_LONG = "Season long";
}
