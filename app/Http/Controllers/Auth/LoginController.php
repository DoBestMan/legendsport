<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm(Redirector $redirector, Request $request)
    {
        $redirector->setIntendedUrl($request->query->get("redirect_url"));
        return view('auth.login');
    }
}
