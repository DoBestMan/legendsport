<?php

namespace App\Providers;

use App\Betting\Bet365\Initaliser;
use App\Betting\Bets365API;
use App\Betting\BettingProvider;
use App\Betting\LegendsOdds\LegendsOdds;
use App\Betting\Lsports\Lsports;
use App\Betting\MultiProvider;
use App\Betting\SportsData\MLB;
use App\Betting\SportsData\NBA;
use App\Betting\SportsData\NFL;
use App\Betting\TestData;
use Illuminate\Support\ServiceProvider;

class BettingApiServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->when([Bets365API::class, Initaliser::class])
            ->needs('$token')
            ->give(env("BETS365_TOKEN"));

        $this->app->when(NBA::class)
            ->needs('$oddsApiKey')
            ->give(env('SPORTSDATA_NBA_ODDS_KEY'));

        $this->app->when(NBA::class)
            ->needs('$scoresApiKey')
            ->give(env('SPORTSDATA_NBA_SCORES_KEY'));

        $this->app->when(MLB::class)
            ->needs('$oddsApiKey')
            ->give(env('SPORTSDATA_MLB_ODDS_KEY'));

        $this->app->when(MLB::class)
            ->needs('$scoresApiKey')
            ->give(env('SPORTSDATA_MLB_SCORES_KEY'));

        $this->app->when(NFL::class)
            ->needs('$oddsApiKey')
            ->give(env('SPORTSDATA_NFL_ODDS_KEY'));

        $this->app->when(NFL::class)
            ->needs('$scoresApiKey')
            ->give(env('SPORTSDATA_NFL_SCORES_KEY'));

        $this->app->tag([TestData::class, NBA::class, MLB::class, NFL::class, Lsports::class, LegendsOdds::class], ['betting_provider']);

        $this->app->when(MultiProvider::class)
            ->needs(BettingProvider::class)
            ->giveTagged('betting_provider');

        $this->app->bind(BettingProvider::class, MultiProvider::class);
    }
}
