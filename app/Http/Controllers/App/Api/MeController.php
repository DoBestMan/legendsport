<?php
namespace App\Http\Controllers\App\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\App\MeTransformer;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function get(Request $request)
    {
        return fractal()
            ->item($request->user(), new MeTransformer())
            ->toArray();
    }
}
