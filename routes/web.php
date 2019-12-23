<?php
    $legendsports = env('LEGENDSPORTS_URL_SUBDOM'). '' .env('APP_URL_DOMAIN');
    $backstage = env('BACKSTAGE_URL_SUBDOM'). '.' .env('APP_URL_DOMAIN');

    route::domain($backstage)->group(function (){
        Route::get('/', 'backstage\HomeController@index')->name('backstage.home');

        Route::resource('/tournaments', 'backstage\TournamentsController');
    });

    route::domain($legendsports)->group(function (){
        Route::get('/', 'app\HomeController@index')->name('app.home');

        Route::get('/tournament', 'app\TournamentController@index')->name('app.tournament');
    });
