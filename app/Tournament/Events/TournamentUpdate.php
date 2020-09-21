<?php
namespace App\Tournament\Events;

use App\Domain\Tournament as TournamentEntity;
use App\Http\Transformers\App\DoctrineTournamentTransformer;
use App\Http\Transformers\App\TournamentTransformer;
use App\Models\Tournament;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

final class TournamentUpdate implements ShouldBroadcastNow
{
    public array $tournament;

    public function __construct($tournament)
    {
        if ($tournament instanceof Tournament) {
            $tournament = $tournament->fresh([
                "events",
                "events.apiEvent",
                "players",
                "players.user",
            ]);

            $this->tournament = fractal()
                ->item($tournament, new TournamentTransformer())
                ->toArray();
        }

        if ($tournament instanceof TournamentEntity) {
            $this->tournament = fractal()
                ->item($tournament, new DoctrineTournamentTransformer())
                ->toArray();
        }
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
