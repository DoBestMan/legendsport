<?php
namespace App\WebSocket;

use BeyondCode\LaravelWebSockets\WebSockets\Channels\ChannelManager;
use Illuminate\Contracts\Broadcasting\Factory as BroadcastFactory;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Broadcaster
{
    private ChannelManager $channelManager;
    private BroadcastFactory $broadcastFactory;

    public function __construct(ChannelManager $channelManager, BroadcastFactory $broadcastFactory)
    {
        $this->channelManager = $channelManager;
        $this->broadcastFactory = $broadcastFactory;
    }

    public function broadcast(ShouldBroadcast $event)
    {
        $appId = $this->broadcastFactory
            ->connection("pusher")
            ->getPusher()
            ->getSettings()["app_id"];
        $channelName = $event->broadcastOn()->name;

        $channel = $this->channelManager->find($appId, $channelName);
        $channel->broadcast([
            "event" => $event->broadcastAs(),
            "data" => $event,
            "channel" => $channelName,
        ]);
    }
}
