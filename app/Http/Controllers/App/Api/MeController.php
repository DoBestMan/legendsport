<?php
namespace App\Http\Controllers\App\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\App\MeTransformer;
use App\Models\User;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function get(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        return fractal()
            ->item($user, new MeTransformer())
            ->toArray();
    }
}
