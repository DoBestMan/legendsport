<?php

use App\Http\Controllers\App\Api\AuthController;
use App\Http\Controllers\App\Api\BetCollection;
use App\Http\Controllers\App\Api\MeController;
use App\Http\Controllers\App\Api\OddCollection;
use App\Http\Controllers\App\Api\SportCollection;
use App\Http\Controllers\App\Api\TournamentBetParlayController;
use App\Http\Controllers\App\Api\TournamentCollection;
use App\Http\Controllers\Backstage\Api\EventCollection;
use Illuminate\Routing\Router;

/** @var Router $router */

$app = env('APP_URL_DOMAIN');
$backstage = env('BACKSTAGE_URL_SUBDOM') . '.' . env('APP_URL_DOMAIN');

$router->domain($app)->group(function (Router $router) {
    $router->get('/tournaments', TournamentCollection::class . '@get');
    $router->get('/sports', SportCollection::class . '@get');
    $router->get('/odds', OddCollection::class . '@get');

    $router->middleware('auth')->group(function (Router $router) {
        $router->post('/logout', AuthController::class . '@logout');
        $router->get('/me', MeController::class . '@get');

        $router->get("/bets", BetCollection::class . "@get");
        $router->post("/tournaments/{tournament}/bets/parlay", TournamentBetParlayController::class . "@post");
    });
});

$router->domain($backstage)->group(function (Router $router) {
    $router->get('/events', EventCollection::class . '@get');
    $router->get('/sports', SportCollection::class . '@get');
});
