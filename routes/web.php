<?php

use App\Http\Controllers\App\View\HomeController as AppHomeController;
use App\Http\Controllers\App\View\TournamentController;
use App\Http\Controllers\Backstage\ConfigController;
use App\Http\Controllers\Backstage\HomeController as BackstageHomeController;
use App\Http\Controllers\Backstage\TournamentsController;

$app = env('APP_URL_DOMAIN');
$backstage = env('BACKSTAGE_URL_SUBDOM') . '.' . env('APP_URL_DOMAIN');

Route::domain($app)->group(function (){
    Route::get('/', AppHomeController::class . '@index')->name('app.home');
    Route::get('/tournament', TournamentController::class . '@index')->name('app.tournament');
});

Route::domain($backstage)->group(function (){
    Route::get('/', BackstageHomeController::class . '@index')->name('backstage.home');

    Route::get('/config', ConfigController::class . '@show')->name('config.show');
    Route::get('/config/edit', ConfigController::class . '@edit')->name('config.edit');
    Route::put('/config', ConfigController::class . '@update')->name('config.update');

    Route::resource('/tournaments', TournamentsController::class);

    Route::post('/tournaments/get-events', TournamentsController::class . '@getEvents');
});
