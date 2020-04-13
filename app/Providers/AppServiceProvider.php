<?php

namespace App\Providers;

use App\Betting\Bets365;
use App\Betting\Bets365API;
use App\Betting\BettingProvider;
use App\Betting\JsonOddAPI;
use App\Services\UserTokenService;
use App\WebSocket\WebSocketHandler;
use BeyondCode\LaravelWebSockets\WebSockets\WebSocketHandler as BaseWebSocketHandler;
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

        $this->app->bind(UserTokenService::class, function () {
            return new UserTokenService(env("APP_KEY"));
        });

        $this->app->bind(BaseWebSocketHandler::class, WebSocketHandler::class);
        $this->app->bind(BettingProvider::class, Bets365::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
