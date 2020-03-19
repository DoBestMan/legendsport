<?php
namespace App\Http\Controllers\App\View;

use App\Http\Controllers\Controller;

class AppController extends Controller
{
    public function index()
    {
        return view('app.index');
    }
}
