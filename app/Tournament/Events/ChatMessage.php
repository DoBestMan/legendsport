<?php
namespace App\Tournament\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

final class ChatMessage implements ShouldBroadcast
{
    public string $id;
    public int $tournamentId;
    public int $userId;
    public string $userName;
    public int $timestamp;
    public string $message;

    public function __construct(int $tournamentId, int $userId, string $userName, string $message)
    {
        $this->tournamentId = $tournamentId;
        $this->userId = $userId;
        $this->userName = $userName;
        $this->message = $message;
        $this->id = uniqid();
        $this->timestamp = intval(microtime(true) * 1000);
    }

    public function broadcastOn()
    {
        return new Channel("chat.tournaments.{$this->tournamentId}");
    }

    public function broadcastAs()
    {
        return "message";
    }
}
