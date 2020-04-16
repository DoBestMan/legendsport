<?php
namespace App\Tournament\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static BetStatus WIN()
 * @method static BetStatus LOSS()
 * @method static BetStatus PUSH()
 * @method static BetStatus PENDING()
 */
final class BetStatus extends Enum
{
    private const WIN = "win";
    private const LOSS = "loss";
    private const PUSH = "push";
    private const PENDING = "pending";
}
