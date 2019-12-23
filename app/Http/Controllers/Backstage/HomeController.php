<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('backstage.home.index');
    }
}