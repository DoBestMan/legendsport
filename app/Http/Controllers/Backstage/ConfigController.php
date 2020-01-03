<?php

namespace App\Http\Controllers\Backstage;

use Illuminate\Http\Request;
use App\Models\Backstage\Config;
use App\Http\Controllers\Controller;

class ConfigController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show()
    {
        return view('backstage.config.show')
            ->with('config', Config::first());
    }

    public function edit()
    {
        return view('backstage.config.edit')
            ->with('config', Config::first());
    }

    public function update(Request $request)
    {
        $config = config::first();

        $config->config = $request->config;
        $config->save();

        return redirect()->route('config.edit');
    }

}