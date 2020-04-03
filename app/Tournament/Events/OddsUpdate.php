<?php
namespace App\Tournament\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OddsUpdate implements ShouldBroadcast
{
    public array $odds;

    public function __construct(array $odds)
    {
        $this->odds = $odds;
    }

    public function broadcastOn()
    {
        return new Channel("general");
    }

    public function broadcastAs()
    {
        return "odds";
    }
}
