<?php
namespace App\User;

use App\Http\Transformers\App\MeTransformer;
use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

final class MeUpdate implements ShouldBroadcast
{
    public array $user;

    public function __construct(User $user)
    {
        $user = $user->fresh(["bets.betEvents.tournamentEvent.apiEvent", "players.bets"]);

        $this->user = fractal()
            ->item($user, new MeTransformer())
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
