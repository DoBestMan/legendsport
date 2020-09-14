<?php

namespace App\Providers;

use App\Betting\BettingProvider;
use App\Betting\LegendsOdds\LegendsOdds;
use App\Betting\MultiProvider;
use App\Betting\TestData;
use Illuminate\Support\ServiceProvider;

class BettingApiServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->tag([TestData::class, LegendsOdds::class], ['betting_provider']);

        $this->app->when(MultiProvider::class)
            ->needs(BettingProvider::class)
            ->giveTagged('betting_provider');

        $this->app->bind(BettingProvider::class, MultiProvider::class);
    }
}
