<?php

namespace App\Providers;

use Acelaya\Doctrine\Type\PhpEnumType;
use App\Betting\Bet365\Initaliser;
use App\Betting\Bets365;
use App\Betting\Bets365API;
use App\Betting\BettingProvider;
use App\Betting\JsonOddAPI;
use App\Betting\TestData;
use App\Repository\OrmRepository;
use App\Repository\Repository;
use App\Repository\RepositoryManager;
use App\Services\UserTokenService;
use App\Tournament\Enums\BetStatus;
use App\WebSocket\WebSocketHandler;
use App\Http\Websockets\Healthcheck;
use BeyondCode\LaravelWebSockets\WebSockets\WebSocketHandler as BaseWebSocketHandler;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(JsonOddAPI::class, function () {
            return new JsonOddAPI(env("JSONODDS_API_KEY"));
        });

        $this->app->bind(Bets365API::class, function () {
            return new Bets365API(env("BETS365_TOKEN"));
        });

        $this->app->bind(Initaliser::class, function () {
            return new Initaliser(env("BETS365_TOKEN"), $this->app->get(EntityManager::class));
        });

        $this->app->bind(UserTokenService::class, function () {
            return new UserTokenService(env("APP_KEY"));
        });

        $this->app->bind(BaseWebSocketHandler::class, WebSocketHandler::class);
        $this->app->bind(BettingProvider::class, Bets365::class);

        $this->app->bind(RepositoryManager::class, function () {
            return new RepositoryManager(function (string $entityClass): Repository {
                return new OrmRepository($entityClass, $this->app->get(EntityManager::class));
            });
        });

        PhpEnumType::registerEnumType(BetStatus::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->app->get('websockets.router')->get('/', Healthcheck::class);
    }
}
