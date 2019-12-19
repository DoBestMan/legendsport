<?php

Route::get('/', 'app\HomeController@index')->name('app.home');

Route::get('/tournament', 'app\TournamentController@index')->name('app.tournament');

