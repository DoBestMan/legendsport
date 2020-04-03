<?php
namespace App\WebSockets;

use App\WebSockets\Handlers\ChatMessageHandler;
use App\WebSockets\Handlers\DummyHandler;
use Illuminate\Contracts\Foundation\Application;

class MessageHandlerFactory
{
    private Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function create(string $event): ?MessageHandler
    {
        switch ($event) {
            case "chat.message":
                return $this->app->make(ChatMessageHandler::class);
            default:
                return null;
        }
    }
}
