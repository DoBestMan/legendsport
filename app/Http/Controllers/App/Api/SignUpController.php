<?php

namespace App\Http\Controllers\App\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class SignUpController extends Controller
{
    public function post(Request $request, AuthManager $authManager)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $this->create($request->all());
        event(new Registered($user));

        $authManager->guard()->login($user);

        return new Response('', Response::HTTP_CREATED);
    }

    private function create(array $data): User
    {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->balance = 1000000;
        $user->save();

        return $user;
    }
}
