<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;

class TournamentsController extends Controller
{
    public function index()
    {
        return view('backstage.legendsports.tournaments.index');
    }

    public function create()
    {

        return view('backstage.legendsports.tournaments.create');
    }

    public function store(Request $request)
    {

        return redirect()->route('backstage.legendsports.tournaments.index');
    }

    public function show()
    {
        return view('backstage.legendsports.tournaments.show');
    }

    public function edit()
    {
        return view('backstage.legendsports.tournaments.edit');
    }

    public function update(Request $request)
    {
        return redirect()->route('backstage.legendsports.tournaments.index');
    }

    private function validation(Request $request)
    {
       //
    }

    public function destroy(Tournaments $tournament)
    {
        $tournament->delete();

        return redirect()->route('backstage.legendsports.tournaments.index');
    }
}