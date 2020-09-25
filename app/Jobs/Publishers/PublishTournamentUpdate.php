<?php

namespace App\Jobs\Publishers;

use App\Domain\Tournament;
use App\Queue\Uniqueable;
use App\Tournament\Events\TournamentUpdate;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Psr\Log\LoggerInterface;

class PublishTournamentUpdate implements ShouldQueue, Uniqueable
{
    public \DateTime $delay;
    private int $tournamentId;

    public function __construct(int $tournamentId)
    {
        $this->delay = Carbon::now()->addSeconds(10);
        $this->tournamentId = $tournamentId;
    }

    public function handle(EntityManager $entityManager, Dispatcher $dispatcher, LoggerInterface $logger)
    {
        $logger->info('Tournament updated: ' . $this->tournamentId);
        $tournament = $entityManager->find(Tournament::class, $this->tournamentId);
        $dispatcher->dispatch(new TournamentUpdate($tournament));
    }

    public function uniqueable()
    {
        return hash('sha256', static::class . '(' . $this->tournamentId . ')');
    }
}
