<?php
namespace App\Tournament;

use MyCLabs\Enum\Enum;

/**
 * @method static BetStatus WIN()
 * @method static BetStatus LOSS()
 * @method static BetStatus PENDING()
 */
final class BetStatus extends Enum
{
    private const WIN = "win";
    private const LOSS = "loss";
    private const PENDING = "pending";
}