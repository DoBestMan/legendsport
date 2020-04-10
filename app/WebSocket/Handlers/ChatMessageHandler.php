<?php
namespace App\WebSocket\Handlers;

use App\Models\User;
use App\Tournament\Events\ChatMessage;
use App\WebSocket\Broadcaster;
use App\WebSocket\MessageHandler;
use BeyondCode\LaravelWebSockets\WebSockets\Channels\ChannelManager;

class ChatMessageHandler implements MessageHandler
{
    private ChannelManager $channelManager;
    private Broadcaster $broadcaster;

    public function __construct(ChannelManager $channelManager, Broadcaster $broadcaster)
    {
        $this->channelManager = $channelManager;
        $this->broadcaster = $broadcaster;
    }

    public function handle(array $data, ?User $user): void
    {
        if (!$user) {
            return;
        }

        $event = new ChatMessage($data["tournamentId"], $user->id, $user->name, $data["message"]);
        $this->broadcaster->broadcast($event);
    }
}
