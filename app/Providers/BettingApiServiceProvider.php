<?php

namespace App\Providers;

use App\Betting\ApiClient;
use App\Betting\BettingProvider;
use App\Betting\LegendsOdds;
use App\Betting\MultiProvider;
use App\Betting\TestData;
use App\Http\Controllers\Backstage\View\BookController;
use Illuminate\Support\ServiceProvider;

class BettingApiServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->when(ApiClient::class)
            ->needs('$baseUrl')
            ->give(fn () => env('LEGENDS_ODDS_URL'));

        $this->app->when(ApiClient::class)
            ->needs('$authToken')
            ->give(fn () => env('LEGENDS_ODDS_TOKEN'));

        $this->app->when(BookController::class)
            ->needs('$baseUrl')
            ->give(fn () => env('LEGENDS_ODDS_URL'));

        $this->app->when(BookController::class)
            ->needs('$authToken')
            ->give(fn () => env('LEGENDS_ODDS_TOKEN'));

        $this->app->tag([TestData::class, LegendsOdds::class], ['betting_provider']);

        $this->app->when(MultiProvider::class)
            ->needs(BettingProvider::class)
            ->giveTagged('betting_provider');

        $this->app->bind(BettingProvider::class, MultiProvider::class);
    }
}
