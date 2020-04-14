<?php
namespace App\Tournament\Events;

use App\Http\Transformers\App\TournamentTransformer;
use App\Models\Tournament;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

final class TournamentUpdate implements ShouldBroadcast
{
    public array $tournament;

    public function __construct(Tournament $tournament)
    {
        $tournament = $tournament->fresh([
            "events",
            "events.apiEvent",
            "players",
            "players.user",
            "players.bets",
        ]);

        $this->tournament = fractal()
            ->item($tournament, new TournamentTransformer())
            ->toArray();
    }

    public function broadcastOn()
    {
        return new Channel("general");
    }

    public function broadcastAs()
    {
        return "tournament";
    }
}
