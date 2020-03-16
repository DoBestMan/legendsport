<?php

use App\Http\Controllers\App\View\HomeController as AppHomeController;
use App\Http\Controllers\App\View\TournamentController as AppTournamentController;
use App\Http\Controllers\Backstage\View\ConfigController;
use App\Http\Controllers\Backstage\View\HomeController as BackstageHomeController;
use App\Http\Controllers\Backstage\View\TournamentController as BackstageTournamentController;
use Illuminate\Routing\Router;

/** @var Router $router */

$app = env('APP_URL_DOMAIN');
$backstage = env('BACKSTAGE_URL_SUBDOM') . '.' . env('APP_URL_DOMAIN');

$router->domain($app)->group(function (Router $router) {
    $router->get('/', AppHomeController::class . '@index')->name('app.home');
    $router->get('/tournament', AppTournamentController::class . '@index')->name('app.tournament');
});

$router->domain($backstage)->group(function (Router $router) {
    $router->get('/', BackstageHomeController::class . '@index')->name('backstage.home');

    $router->get('/config', ConfigController::class . '@show')->name('config.show');
    $router->get('/config/edit', ConfigController::class . '@edit')->name('config.edit');
    $router->put('/config', ConfigController::class . '@update')->name('config.update');

    $router->resource('/tournaments', BackstageTournamentController::class);
});
