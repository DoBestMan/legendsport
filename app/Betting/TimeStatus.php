<?php
namespace App\Betting;

use MyCLabs\Enum\Enum;

/**
 * @method static TimeStatus NOT_STARTED()
 * @method static TimeStatus IN_PLAY()
 * @method static TimeStatus ENDED()
 * @method static TimeStatus CANCELED()
 */
class TimeStatus extends Enum
{
    private const NOT_STARTED = "not_started";
    private const IN_PLAY = "in_play";
    private const ENDED = "ended";
    private const CANCELED = "canceled";
}
