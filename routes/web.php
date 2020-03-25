<?php

use App\Http\Controllers\App\View\AppController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Backstage\View\ConfigController;
use App\Http\Controllers\Backstage\View\HomeController as BackstageHomeController;
use App\Http\Controllers\Backstage\View\TournamentController as BackstageTournamentController;
use Illuminate\Routing\Router;

/** @var Router $router */

$app = env('APP_URL_DOMAIN');
$backstage = env('BACKSTAGE_URL_SUBDOM') . '.' . env('APP_URL_DOMAIN');

$router->domain($app)->group(function (Router $router) {
    $router->get('login', LoginController::class . '@showLoginForm')->name('login');
    $router->post('login', LoginController::class . '@login');
    $router->post('logout', LoginController::class . '@logout')->name('logout');
    $router->get('register', RegisterController::class . '@showRegistrationForm')->name('register');
    $router->post('register', RegisterController::class . '@register');

    $router->get('/{any}', AppController::class . '@index')->where('any', '.*');
});

$router->domain($backstage)->group(function (Router $router) {
    $router->get('/', BackstageHomeController::class . '@index')->name('backstage.home');

    $router->get('/config', ConfigController::class . '@show')->name('config.show');
    $router->get('/config/edit', ConfigController::class . '@edit')->name('config.edit');
    $router->put('/config', ConfigController::class . '@update')->name('config.update');

    $router->resource('/tournaments', BackstageTournamentController::class);
});
