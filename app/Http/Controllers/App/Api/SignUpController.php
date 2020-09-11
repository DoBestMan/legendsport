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
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255', 'unique:users'],
                'firstname' => ['required', 'string', 'max:255'],
                'lastname' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'dob' => ['required', 'date', 'before:-18 years'],
            ],
            ['dob.before' => 'You must be over 18 years old to register an account']
        );

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
        $user->balance = 0;
        $user->firstname = $data['firstname'];
        $user->lastname = $data['lastname'];
        $user->date_of_birth = $data['dob'];
        $user->is_bot = 0;
        $user->save();

        return $user;
    }
}
