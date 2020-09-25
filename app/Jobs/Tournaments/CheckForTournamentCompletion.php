<?php

namespace App\Jobs\Tournaments;

use App\Jobs\Publishers\PublishTournamentUpdate;
use App\Jobs\Publishers\PublishUserUpdate;
use App\Models\Tournament;
use App\Queue\Uniqueable;
use App\Tournament\TournamentCompletionService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Dispatcher;
use Psr\Log\LoggerInterface;

class CheckForTournamentCompletion implements ShouldQueue, Uniqueable
{
    public \DateTime $delay;
    private int $tournamentId;

    public function __construct(int $tournamentId)
    {
        $this->delay = Carbon::now()->addSeconds(2);
        $this->tournamentId = $tournamentId;
    }

    public function handle(TournamentCompletionService $tournamentCompletionService, Dispatcher $dispatcher, LoggerInterface $logger)
    {
        $logger->info('Checking Tournament completion status: ' . $this->tournamentId);
        $tournament = Tournament::find($this->tournamentId);

        if ($tournamentCompletionService->updateState($tournament)) {
            $logger->info('Tournament has finished: ' . $this->tournamentId);
            $dispatcher->dispatch(new PublishTournamentUpdate($this->tournamentId));

            foreach ($tournament->players as $tournamentPlayer) {
                $dispatcher->dispatch(new PublishUserUpdate($tournamentPlayer->user_id));
            }
        }
    }

    public function uniqueable()
    {
        return hash('sha256', static::class . '(' . $this->tournamentId . ')');
    }
}
