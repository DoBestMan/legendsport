<?php

namespace App\Http\Controllers\App;

use JavaScript;
use App\Http\Controllers\Controller;
use App\Models\Backstage\Tournaments;
use App\Models\Backstage\TournamentSports;

class HomeController extends Controller
{
    public function index()
    {
        $userTournamentsActives = [
            [
                'id' => 1,
                'name' => 'All sports Fre4all',
            ], [
                'id' => 2,
                'name' => 'Weekend NFL',
            ], [
                'id' => 3,
                'name' => 'Thursday Basketball',
            ],
        ];

        $tournaments = Tournaments::get();
        $tempSport = [
            'sport_id' => [],
        ];
        $tournamentsWithSport = [];

        foreach ($tournaments as $tournament) {
            $sports = TournamentSports::where('tournament_id', $tournament->id)->get('sport_id');
            foreach ($sports as $sport) {
                array_push($tempSport['sport_id'],$sport['sport_id']);
            }
            $tournament = array_merge($tournament->toArray(), $tempSport);
            array_push($tournamentsWithSport, $tournament);
        }

        Javascript::put([
            'tournaments' => $tournamentsWithSport,
        ]);

        return view('app.home.index')
            ->with('userTournaments', $userTournamentsActives);
    }
}
