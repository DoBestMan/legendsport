<?php

namespace App\Providers;

use Acelaya\Doctrine\Type\PhpEnumType;
use App\Betting\Bet365\Initaliser;
use App\Betting\Bets365;
use App\Betting\Bets365API;
use App\Betting\BettingProvider;
use App\Betting\MultiProvider;
use App\Betting\SportsData\NBA;
use App\Betting\TestData;
use App\Betting\TimeStatus;
use App\Queue\DatabaseConnector;
use App\Repository\OrmRepository;
use App\Repository\Repository;
use App\Repository\RepositoryManager;
use App\Services\UserTokenService;
use App\Tournament\Enums\BetStatus;
use App\Tournament\Enums\TournamentState;
use App\WebSocket\WebSocketHandler;
use App\Http\Websockets\Healthcheck;
use BeyondCode\LaravelWebSockets\WebSockets\WebSocketHandler as BaseWebSocketHandler;
use Doctrine\ORM\EntityManager;
use Illuminate\Queue\QueueManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(RepositoryManager::class, function () {
            return new RepositoryManager(function (string $entityClass): Repository {
                return new OrmRepository($entityClass, $this->app->get(EntityManager::class));
            });
        });

        $this->app->when(UserTokenService::class)
            ->needs('$secret')
            ->give(env("APP_KEY"));

        $this->app->bind(BaseWebSocketHandler::class, WebSocketHandler::class);

        PhpEnumType::registerEnumType(BetStatus::class);
        PhpEnumType::registerEnumType(TournamentState::class);
        PhpEnumType::registerEnumType(TimeStatus::class);
    }

    public function boot()
    {
        $this->app->get('websockets.router')->get('/', Healthcheck::class);
        /** @var QueueManager $queue */
        $queue = $this->app['queue'];
        $queue->addConnector('uniquedbqueue', function () {
            return new DatabaseConnector($this->app['db']);
        });
    }
}
