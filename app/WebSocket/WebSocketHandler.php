<?php

namespace App\WebSocket;

use App\Services\UserTokenService;
use BeyondCode\LaravelWebSockets\WebSockets\Channels\ChannelManager;
use BeyondCode\LaravelWebSockets\WebSockets\WebSocketHandler as BaseWebSocketHandler;
use Illuminate\Support\Arr;
use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;

class WebSocketHandler extends BaseWebSocketHandler
{
    private MessageHandlerFactory $messageHandlerFactory;
    private UserTokenService $userTokenService;

    public function __construct(
        ChannelManager $channelManager,
        MessageHandlerFactory $messageHandlerFactory,
        UserTokenService $userTokenService
    ) {
        parent::__construct($channelManager);
        $this->messageHandlerFactory = $messageHandlerFactory;
        $this->userTokenService = $userTokenService;
    }

    public function onMessage(ConnectionInterface $connection, MessageInterface $message)
    {
        $payload = json_decode($message->getPayload(), true);
        $handler = $this->messageHandlerFactory->create($payload["event"]);

        if ($handler) {
            $data = Arr::get($payload, "data.data");
            $token = Arr::get($payload, "data.token") ?? "";
            $user = $this->userTokenService->getUser($token);
            $handler->handle($data, $user);
        }

        parent::onMessage($connection, $message);
    }
}
