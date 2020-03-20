<?php
namespace App\Http\Controllers\Backstage\View;

use Illuminate\Http\Response;
use JavaScript;
use Illuminate\Http\Request;
use App\Models\Config;
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
            'commission' => $config->config['commission'],
            'chips' => $config->config['chips'],
            'keepCompleted' => $config->config['keep_completed'],
        ]);

        return view('backstage.config.show')->with('config', $config);
    }

    public function edit()
    {
        $config = Config::first();

        JavaScript::put([
            'commission' => $config->config['commission'],
            'chips' => $config->config['chips'],
            'keepCompleted' => $config->config['keep_completed'],
        ]);

        return view('backstage.config.edit')->with('config', $config);
    }

    public function update(Request $request)
    {
        $config = Config::first();

        $config->config = $request->config;
        $config->save();

        return new Response(Response::HTTP_NO_CONTENT);
    }
}
