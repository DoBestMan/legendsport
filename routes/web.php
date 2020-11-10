<?php

use App\Http\Controllers\App\View\AppController;
use App\Http\Controllers\Backstage\View\BookController;
use App\Http\Controllers\Backstage\View\ConfigController;
use App\Http\Controllers\Backstage\Api\SignInController as BackstageSignInController;
use App\Http\Controllers\Backstage\View\HomeController as BackstageHomeController;
use App\Http\Controllers\Backstage\View\TournamentController as BackstageTournamentController;
use App\Http\Controllers\Backstage\View\AdminController as BackstageUserController;
use App\Http\Controllers\Backstage\View\TournamentDashboardController;
use App\Http\Controllers\Backstage\View\WithdrawalController;
use App\Http\Controllers\Backstage\View\UserController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Broadcast;

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

        $router->get('/tournaments/dashboard', TournamentDashboardController::class . '@index')->name('tournaments.dashboard');
        $router->post('/tournaments/{tournament}/check-complete', BackstageTournamentController::class . '@checkForCompletion');
        $router->post('/tournaments/{tournament}/grade-events', BackstageTournamentController::class . '@gradeEvents');
        $router->resource('/tournaments', BackstageTournamentController::class);

        $router->resource('/admins', BackstageUserController::class);

        $router->get('/book/active', BookController::class . '@active')->name('book.active');
        $router->post('/book/manage/{id}/cancel', BookController::class . '@cancel');
        $router->post('/book/manage/{id}/start', BookController::class . '@start');
        $router->post('/book/manage/{id}/finish', BookController::class . '@finish');

        $router->get('/users/export', UserController::class . '@export')->name('users.export');
        $router->get('/withdrawals/pending', WithdrawalController::class . '@pending')->name('withdrawals.pending');
        $router->post('/withdrawals/{id}/process', WithdrawalController::class . '@process');

        $router
            ->post('/logout', BackstageSignInController::class . '@logout')
            ->name("backstage.logout");
    });

    $router->get('/signin', BackstageSignInController::class . '@showLoginForm');
    $router->post('/signin', BackstageSignInController::class . '@login')->name("backstage.signin");
});
