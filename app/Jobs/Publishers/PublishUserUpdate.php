<?php

namespace App\Jobs\Publishers;

use App\Domain\Tournament;
use App\Models\User;
use App\Queue\Uniqueable;
use App\Tournament\Events\TournamentUpdate;
use App\User\MeUpdate;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Psr\Log\LoggerInterface;

class PublishUserUpdate implements ShouldQueue, Uniqueable
{
    public \DateTime $delay;
    private int $userId;

    public function __construct(int $userId)
    {
        $this->delay = Carbon::now()->addSeconds(10);
        $this->userId = $userId;
    }

    public function handle(Dispatcher $dispatcher, LoggerInterface $logger)
    {
        $logger->info('User updated: ' . $this->userId);
        $user = User::find($this->userId);
        $dispatcher->dispatch(new MeUpdate($user));
    }

    public function uniqueable()
    {
        return hash('sha256', static::class . '(' . $this->userId . ')');
    }
}
