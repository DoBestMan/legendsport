<?php
namespace App\Http\Controllers\App\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function get(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        return [
            "id"      => $user->id,
            "name"    => $user->name,
            "balance" => $user->balance,
        ];
    }
}
