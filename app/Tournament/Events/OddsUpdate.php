<?php
namespace App\Tournament\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

final class OddsUpdate implements ShouldBroadcastNow
{
    public array $odds;
    private bool $incremental;

    public function __construct(array $odds, bool $incremental = false)
    {
        $this->odds = $odds;
        $this->incremental = $incremental;
    }

    public function broadcastOn()
    {
        return new Channel("general");
    }

    public function broadcastAs()
    {
        return $this->incremental ? 'incremental-odds' : 'odds';
    }
}
