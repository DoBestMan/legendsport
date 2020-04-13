<?php

use App\Http\Controllers\App\Api\ResultCollection;
use App\Http\Controllers\App\Api\SignInController as AppSignInController;
use App\Http\Controllers\App\Api\MeController;
use App\Http\Controllers\App\Api\OddCollection;
use App\Http\Controllers\App\Api\SignUpController;
use App\Http\Controllers\App\Api\SportCollection;
use App\Http\Controllers\App\Api\TournamentBetParlayController;
use App\Http\Controllers\App\Api\TournamentBetStraightController;
use App\Http\Controllers\App\Api\TournamentCollection;
use App\Http\Controllers\App\Api\TournamentRegisterController;
use App\Http\Controllers\Backstage\Api\EventCollection;
use Illuminate\Routing\Router;

/** @var Router $router */

$app = env('APP_URL_DOMAIN');
$backstage = env('BACKSTAGE_URL_SUBDOM') . '.' . env('APP_URL_DOMAIN');

$router->domain($app)->group(function (Router $router) {
    $router->get('/tournaments', TournamentCollection::class . '@get');
    $router->get('/sports', SportCollection::class . '@get');
    $router->get('/odds', OddCollection::class . '@get');
    $router->get('/results', ResultCollection::class . '@get');

    $router->post('/signin', AppSignInController::class . '@login');
    $router->post('/signup', SignUpController::class . '@post');

    $router->middleware('auth')->group(function (Router $router) {
        $router->post('/logout', AppSignInController::class . '@logout');
        $router->get('/me', MeController::class . '@get');

        $router->post(
            "/tournaments/{tournament}/register",
            TournamentRegisterController::class . "@post",
        );
        $router->post(
            "/tournaments/{tournament}/bets/parlay",
            TournamentBetParlayController::class . "@post",
        );
        $router->post(
            "/tournaments/{tournament}/bets/straight",
            TournamentBetStraightController::class . "@post",
        );
    });
});

$router
    ->domain($backstage)
    ->middleware('auth:backstage')
    ->group(function (Router $router) {
        $router->get('/events', EventCollection::class . '@get');
        $router->get('/sports', SportCollection::class . '@get');
    });
