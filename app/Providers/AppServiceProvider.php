<?php

namespace App\Providers;

use App\Services\JsonOddApiService;
use App\Services\UserTokenService;
use App\Betting\BettingProvider;
use App\Betting\JsonOddAPI;
use App\WebSockets\WebSocketHandler;
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
        $this->app->bind(JsonOddApiService::class, function () {
            return new JsonOddApiService(env("JSONODDS_API_KEY"));
        });

        $this->app->bind(UserTokenService::class, function () {
            return new UserTokenService(env("APP_KEY"));
        });

        $this->app->bind(BaseWebSocketHandler::class, WebSocketHandler::class);
        $this->app->bind(BettingProvider::class, JsonOddAPI::class);
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
