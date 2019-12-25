<?php

namespace App\Http\Controllers\Backstage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Legendsports\Tournaments;
use App\Models\Legendsports\Config;

class TournamentsController extends Controller
{
    public function index()
    {
        $tournaments = Tournaments::paginate(10);

        $numFirstItemPage = $tournaments->firstItem();

        return view('backstage.tournaments.index')
            ->with('tournaments', $tournaments)
            ->with('tournament', null)
            ->with('numFirstItemPage', $numFirstItemPage);
    }

    public function create()
    { 
        return view('backstage.tournaments.create')
            ->with('tournaments', null)
            ->with('tournament', null)
            ->with('config', Config::first())
            ->with('numFirstItemPage', 0);
    }

    public function store(Request $request)
    {
        $tournament = new Tournaments;
        $tournament->avatar = $request->avatar;
        $tournament->name = $request->name;
        $tournament->type = $request->type;
        $tournament->prize_pool = $request->prize_pool;
        $tournament->players_limit = $request->players_limit;
        $tournament->buy_in = $request->buy_in;
        $tournament->chips = $request->chips;
        $tournament->commission = $request->commission;
        $tournament->late_register = $request->late_register;
        $tournament->late_register_rule = 1;
        $tournament->state = $request->state;
        $tournament->prizes = 22;
        $tournament->save();

        return redirect()->route('tournaments.index');
    }

    public function show(Tournaments $tournament)
    {
        return view('backstage.tournaments.show')
            ->with('tournaments', null)
            ->with('tournament', $tournament)
            ->with('numFirstItemPage', 0);
    }

    public function edit(Tournaments $tournament)
    {
        return view('backstage.tournaments.edit')
            ->with('tournaments', null)
            ->with('tournament', $tournament)
            ->with('numFirstItemPage', 0);
    }

    public function update(Request $request, Tournaments $tournament)
    {
        $tournament->avatar = $request->avatar;
        $tournament->name = $request->name;
        $tournament->type = $request->type;
        $tournament->prize_pool = $request->prize_pool;
        $tournament->players_limit = $request->players_limit;
        $tournament->buy_in = $request->buy_in;
        $tournament->chips = $request->chips;
        $tournament->commission = $request->commission;
        $tournament->late_register = $request->late_register;
        $tournament->late_register_rule = 1;
        $tournament->state = $request->state;
        $tournament->prizes = 22;
        $tournament->save();

        return redirect()->route('tournaments.index');
    }

    private function validation(Request $request)
    {
       //
    }

    public function destroy(Tournaments $tournament)
    {
        $tournament->delete();

        return redirect()->route('tournaments.index');
    }
}