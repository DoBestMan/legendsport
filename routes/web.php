<?php

use App\Http\Controllers\App\View\AppController;
use App\Http\Controllers\Backstage\View\ConfigController;
use App\Http\Controllers\Backstage\Api\SignInController as BackstageSignInController;
use App\Http\Controllers\Backstage\View\HomeController as BackstageHomeController;
use App\Http\Controllers\Backstage\View\TournamentController as BackstageTournamentController;
use App\Http\Controllers\Backstage\View\UserController as BackstageUserController;
use Illuminate\Routing\Router;

/** @var Router $router */

$app = env('APP_URL_DOMAIN');
$backstage = env('BACKSTAGE_URL_SUBDOM') . '.' . env('APP_URL_DOMAIN');

$router->domain($app)->group(function (Router $router) {
    $router->get('/{any}', AppController::class . '@index')->where('any', '.*');
});

$router->domain($backstage)->group(function (Router $router) {
    $router->middleware('auth:backstage')->group(function (Router $router) {
        $router->get('/', BackstageHomeController::class . '@index')->name('backstage.home');
        $router->get('/config', ConfigController::class . '@show')->name('config.show');
        $router->get('/config/edit', ConfigController::class . '@edit')->name('config.edit');
        $router->put('/config', ConfigController::class . '@update')->name('config.update');
        $router->resource('/tournaments', BackstageTournamentController::class);
        $router->resource('/users', BackstageUserController::class);

        $router
            ->post('/logout', BackstageSignInController::class . '@logout')
            ->name("backstage.logout");
    });

    $router->get('/signin', BackstageSignInController::class . '@showLoginForm');
    $router->post('/signin', BackstageSignInController::class . '@login')->name("backstage.signin");
});
