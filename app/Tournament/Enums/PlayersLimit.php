<?php
namespace App\Tournament\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static PlayersLimit HEADS_UP()
 * @method static PlayersLimit SINGLE_TABLE()
 * @method static PlayersLimit UNLIMITED()
 */
final class PlayersLimit extends Enum
{
    private const HEADS_UP = "Heads-Up";
    private const SINGLE_TABLE = "Single table";
    private const UNLIMITED = "Unlimited";
}
