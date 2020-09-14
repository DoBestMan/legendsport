<?php

namespace App\Providers;

use App\Betting\Bet365\Initaliser;
use App\Betting\Bets365API;
use App\Betting\BettingProvider;
use App\Betting\LegendsOdds\LegendsOdds;
use App\Betting\MultiProvider;
use App\Betting\TestData;
use Illuminate\Support\ServiceProvider;

class BettingApiServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->when([Bets365API::class, Initaliser::class])
            ->needs('$token')
            ->give(env("BETS365_TOKEN"));

        $this->app->tag([TestData::class, LegendsOdds::class], ['betting_provider']);

        $this->app->when(MultiProvider::class)
            ->needs(BettingProvider::class)
            ->giveTagged('betting_provider');

        $this->app->bind(BettingProvider::class, MultiProvider::class);
    }
}
