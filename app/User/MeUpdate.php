<?php
namespace App\User;

use App\Domain\User as UserEntity;
use App\Http\Transformers\App\MeTransformer;
use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

final class MeUpdate implements ShouldBroadcastNow
{
    public array $user;

    public function __construct($user)
    {
        if ($user instanceof UserEntity) {
            $user = User::find($user->getId());
        }

        $this->user = fractal()
            ->item($user->fresh(), new MeTransformer())
            ->toArray();
    }

    public function broadcastOn()
    {
        return new PrivateChannel("user.{$this->user['id']}");
    }

    public function broadcastAs()
    {
        return "me";
    }
}
