<?php
namespace App\WebSockets;

use App\Models\User;

interface MessageHandler
{
    public function handle(array $data, ?User $user): void;
}
