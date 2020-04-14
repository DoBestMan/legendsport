<?php
namespace App\Tournament\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

final class ResultsUpdate implements ShouldBroadcast
{
    public array $results;

    public function __construct(array $results)
    {
        $this->results = $results;
    }

    public function broadcastOn()
    {
        return new Channel("general");
    }

    public function broadcastAs()
    {
        return "results";
    }
}
