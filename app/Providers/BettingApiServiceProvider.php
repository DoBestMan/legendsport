<?php

namespace App\Providers;

use Acelaya\Doctrine\Type\PhpEnumType;
use App\Betting\Bet365\Initaliser;
use App\Betting\Bets365;
use App\Betting\Bets365API;
use App\Betting\BettingProvider;
use App\Betting\MultiProvider;
use App\Betting\SportsData\MLB;
use App\Betting\SportsData\NBA;
use App\Betting\TestData;
use App\Betting\TimeStatus;
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
use Illuminate\Support\ServiceProvider;

class BettingApiServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->when([Bets365API::class, Initaliser::class])
            ->needs('$token')
            ->give(env("BETS365_TOKEN"));

        $this->app->when(NBA::class)
            ->needs('$apiKey')
            ->give(env('SPORTSDATA_NBA_ODDS_KEY'));

        $this->app->when(MLB::class)
            ->needs('$apiKey')
            ->give(env('SPORTSDATA_MLB_ODDS_KEY'));

        $this->app->when(UserTokenService::class)
            ->needs('$secret')
            ->give(env("APP_KEY"));

        $this->app->tag([Bets365::class, TestData::class, NBA::class, MLB::class], ['betting_provider']);

        $this->app->when(MultiProvider::class)
            ->needs(BettingProvider::class)
            ->giveTagged('betting_provider');

        $this->app->bind(BettingProvider::class, MultiProvider::class);
    }

    public function boot()
    {
        $this->app->get('websockets.router')->get('/', Healthcheck::class);
    }
}
