<?php
namespace App\Betting;

use App\Tournament\Enums\BetStatus;
use MyCLabs\Enum\Enum;

/**
 * @method static Settlement WON()
 * @method static Settlement LOST()
 * @method static Settlement PUSH()
 */
class Settlement extends Enum
{
    private const WON = 'won';
    private const LOST = 'lost';
    private const PUSH = 'push';

    public static function fromApiSettlement(?string $status): ?Settlement
    {
        switch ($status) {
            case 'Won':
                return Settlement::WON();
            case 'Lost':
                return Settlement::LOST();
            case 'Push':
                return Settlement::PUSH();
            default:
                return null;
        }
    }

    public function toBetStatus(): BetStatus
    {
        switch (true) {
            case $this->equals(Settlement::WON()):
                return BetStatus::WIN();
            case $this->equals(Settlement::PUSH()):
                return BetStatus::PUSH();
            case $this->equals(Settlement::LOST()):
                return BetStatus::LOSS();
        }
    }
}
