<?php
namespace App\Betting;

use MyCLabs\Enum\Enum;

/**
 * @method static TimeStatus NOT_STARTED()
 * @method static TimeStatus TO_BE_FIXED()
 * @method static TimeStatus IN_PLAY()
 * @method static TimeStatus ENDED()
 * @method static TimeStatus CANCELED()
 */
class TimeStatus extends Enum
{
    private const NOT_STARTED = "not_started";
    private const TO_BE_FIXED = "to_be_fixed";
    private const IN_PLAY = "in_play";
    private const ENDED = "ended";
    private const CANCELED = "canceled";

    public static function fromApiStatus(string $status): TimeStatus
    {
        switch ($status) {
            case 'upcoming':
                return TimeStatus::NOT_STARTED();
            case 'inplay':
                return TimeStatus::IN_PLAY();
            case 'ended':
                return TimeStatus::ENDED();
            case 'cancelled':
                return TimeStatus::CANCELED();
            default:
                return TimeStatus::IN_PLAY();
        }
    }
}
