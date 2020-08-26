<?php

namespace App\Betting\SportsData;

use App\Betting\BettingProvider;
use App\Betting\MultiProvider;
use App\Queue\Uniqueable;
use App\Tournament\Events\TournamentUpdate;
use App\Tournament\SportEventResultProcessor;
use App\Tournament\TournamentCompletionService;
use App\User\MeUpdate;
use Carbon\Carbon;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Psr\Log\LoggerInterface;
use Throwable;

class FetchResultsForDate implements ShouldQueue, Uniqueable
{
    private string $date;
    private string $provider;
    public \DateTime $delay;
    private int $offset;

    public function __construct(\DateTime $date, string $provider, int $offset)
    {
        $this->delay = Carbon::now()->addSeconds($offset);
        $this->date = $date->format('Y-m-d');
        $this->provider = $provider;
        $this->offset = $offset;
    }

    public function handle(
        MultiProvider $bettingProvider,
        TournamentCompletionService $tournamentStateService,
        SportEventResultProcessor $sportEventResultProcessor,
        LoggerInterface $logger,
        Dispatcher $dispatcher
    ) {
        $logger->info('Fetching results for games on: ' . $this->date);

        $results = $bettingProvider->getProvider($this->provider)->getResultsForDate($this->date);

        try {
            $sportEventResultProcessor->processMultiple($results);
        } catch (Throwable $e) {
            $logger->error($e->getMessage(), ["exception" => $e]);
        }

        foreach ($sportEventResultProcessor->getTournamentsUpdated() as $tournament) {
            $logger->info('Tournament updated: ' . $tournament->getId());
            $tournamentStateService->updateState($tournament);
            $dispatcher->dispatch(new TournamentUpdate($tournament));
        }

        // Inform about updated users bets
        foreach ($sportEventResultProcessor->getUsersUpdated() as $user) {
            $logger->info('User updated: ' . $user->getId());
            $dispatcher->dispatch(new MeUpdate($user));
        }

        $this->delay = Carbon::now()->addSeconds(15);
    }

    public function uniqueable()
    {
        return hash('sha256', static::class . '(' . $this->date . ', ' . $this->provider . ', ' . $this->offset . ')');
    }
}
