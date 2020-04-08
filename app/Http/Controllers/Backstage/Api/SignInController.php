<?php
namespace App\Http\Controllers\Backstage\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class SignInController extends Controller
{
    use AuthenticatesUsers;

    public function showLoginForm()
    {
        return view('backstage.auth.login');
    }

    public function username()
    {
        return 'name';
    }

    protected function guard()
    {
        return Auth::guard("backstage");
    }
}
