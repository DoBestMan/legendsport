<?php
namespace App\Http\Controllers\Backstage\View;

use App\Models\ApiEvent;
use App\Models\Tournament;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use JavaScript;

class UserController
{
    public function index()
    {
        $users = User::paginate(10);
        JavaScript::put([]);
        return view('backstage.users.index')->with('users', $users);
    }

    public function create()
    {
        JavaScript::put([]);
        return view('backstage.users.create');
    }

    public function show(User $user)
    {
        JavaScript::put([
            'name' => $user->name,
            'email' => $user->email,
            'balance' => $user->balance,
        ]);

        return view('backstage.users.show');
    }

    public function edit(User $user)
    {
        JavaScript::put([
            'name' => $user->name,
            'email' => $user->email,
            'balance' => $user->balance,
        ]);

        return view('backstage.users.edit');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'balance' => ['required', 'numeric', 'min:0'],
            'password' => ['required', 'string'],
        ]);

        $user = new User();
        $user->name = $request->request->get('name');
        $user->email = $request->request->get('email');
        $user->balance = $request->request->get('balance');
        $user->password = $request->request->get('password');
        $user->save();

        return new Response('', Response::HTTP_CREATED);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::table())->ignoreModel($user),
            ],
            'balance' => ['required', 'numeric', 'min:0'],
            'password' => ['string'],
        ]);

        $user->name = $request->request->get('name');
        $user->email = $request->request->get('email');
        $user->balance = $request->request->get('balance');
        if ($request->request->get('password')) {
            $user->password = Hash::make($request->request->get('password'));
        }
        $user->save();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
