<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;

class TournamentController extends Controller
{
    public function index()
    {
        return view('app.tournament.index');
    }

}