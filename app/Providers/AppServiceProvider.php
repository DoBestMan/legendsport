<?php

namespace App\Providers;

use App\Services\JsonOddApiService;
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
