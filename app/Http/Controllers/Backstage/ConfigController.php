<?php

namespace App\Http\Controllers\Backstage;

use JavaScript;
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
        $config = Config::first();

        JavaScript::put([
            'commission' => $config->$config['commission'],
            'chips' => $config->$config['chips'],
        ]);

        return view('backstage.config.show')
            ->with('config', $config);
    }

    public function edit()
    {
        $config = Config::first();

        JavaScript::put([
            'commission' => $config->$config['commission'],
            'chips' => $config->$config['chips'],
        ]);

        return view('backstage.config.edit')
            ->with('config', $config);
    }

    public function update(Request $request)
    {
        $config = config::first();

        $config->config = $request->config;
        $config->save();

        return redirect()->route('config.edit');
    }

}