<?php
    $app = env('APP_URL_DOMAIN');
    $backstage = env('BACKSTAGE_URL_SUBDOM') . '.' . env('APP_URL_DOMAIN');

    route::domain($app)->group(function (){
        Route::get('/', 'App\HomeController@index')->name('app.home');

        Route::get('/tournament', 'App\TournamentController@index')->name('app.tournament');
    });

    route::domain($backstage)->group(function (){
        Route::get('/', 'Backstage\HomeController@index')->name('backstage.home');

        Route::get('/config', 'Backstage\ConfigController@show')->name('config.show');
        Route::get('/config/edit', 'Backstage\ConfigController@edit')->name('config.edit');
        Route::put('/config', 'Backstage\ConfigController@update')->name('config.update');

        Route::resource('/tournaments', 'Backstage\TournamentsController');

        Route::post('/tournaments/get-events', 'Backstage\TournamentsController@getEvents');
    });
