<?php
namespace App\Tournament\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static TournamentState ANNOUNCED()
 * @method static TournamentState REGISTERING()
 * @method static TournamentState LATE_REGISTERING()
 * @method static TournamentState RUNNING()
 * @method static TournamentState COMPLETED()
 * @method static TournamentState CANCELED()
 */
final class TournamentState extends Enum
{
    private const ANNOUNCED = "Announced";
    private const REGISTERING = "Registering";
    private const LATE_REGISTERING = "Late registering";
    private const RUNNING = "Running";
    private const COMPLETED = "Completed";
    private const CANCELED = "Cancel";

    public function isOneOf(Enum ...$enums): bool
    {
        foreach ($enums as $enum) {
            if ($enum->equals($this)) {
                return true;
            }
        }

        return false;
    }
}
