<?php
namespace App\Http\Controllers\App\View;

use App\Http\Controllers\Controller;

class TournamentController extends Controller
{
    public function index()
    {
        return view('app.tournament.index');
    }
}
