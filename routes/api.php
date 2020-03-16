<?php

use App\Http\Controllers\App\Api\TournamentCollection;
use Illuminate\Http\Request;

$app = env('APP_URL_DOMAIN');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::domain($app)->group(function (){
    Route::get("/tournaments", TournamentCollection::class . "@get");
});
