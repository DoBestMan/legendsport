<?php
namespace App\WebSocket;

use App\Models\User;

interface MessageHandler
{
    public function handle(array $data, ?User $user): void;
}
