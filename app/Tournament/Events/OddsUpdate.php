<?php
namespace App\Tournament\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

final class OddsUpdate implements ShouldBroadcast
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
