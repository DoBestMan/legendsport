<?php
namespace App\Http\Controllers\Backstage\View;

use App\Models\Admin;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JavaScript;

class AdminController
{
    public function index()
    {
        $admins = Admin::paginate(10);
        JavaScript::put([]);
        return view('backstage.admins.index')->with('admins', $admins);
    }

    public function create()
    {
        JavaScript::put([]);
        return view('backstage.admins.create');
    }

    public function show(Admin $admin)
    {
        JavaScript::put([
            'name' => $admin->name,
        ]);

        return view('backstage.admins.show');
    }

    public function edit(Admin $admin)
    {
        JavaScript::put([
            'name' => $admin->name,
        ]);

        return view('backstage.admins.edit');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        $admin = new Admin();
        $admin->name = $request->request->get('name');
        $admin->password = $request->request->get('password');
        $admin->save();

        return new Response('', Response::HTTP_CREATED);
    }

    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['string'],
        ]);

        $admin->name = $request->request->get('name');
        if ($request->request->get('password')) {
            $admin->password = Hash::make($request->request->get('password'));
        }
        $admin->save();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
