<?php

use App\Http\Controllers\App\Api\SportCollection;
use App\Http\Controllers\App\Api\TournamentCollection;
use App\Http\Controllers\Backstage\Api\EventCollection;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

/** @var Router $router */

$app = env('APP_URL_DOMAIN');
$backstage = env('BACKSTAGE_URL_SUBDOM') . '.' . env('APP_URL_DOMAIN');

$router->middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

$router->domain($app)->group(function (Router $router) {
    $router->get('/tournaments', TournamentCollection::class . '@get');
    $router->get('/sports', SportCollection::class . '@get');
});

$router->domain($backstage)->group(function (Router $router) {
    $router->get('/events', EventCollection::class . '@get');
});
