<?php
namespace App\Http\Controllers\App\View;

use App\Http\Controllers\Controller;

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

        return view('app.home.index')
            ->with('userTournaments', $userTournamentsActives);
    }
}
