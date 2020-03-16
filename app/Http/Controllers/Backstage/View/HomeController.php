<?php
namespace App\Http\Controllers\Backstage\View;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('backstage.home.index');
    }
}
