<?php
namespace App\Http\Controllers\App\Api;

use App\Domain\User;
use App\Http\Controllers\Controller;
use App\Http\Transformers\App\MeTransformer;
use Doctrine\ORM\EntityManager;
use Illuminate\Hashing\HashManager;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MeController extends Controller
{
    public function get(Request $request)
    {
        return fractal()
            ->item($request->user(), new MeTransformer())
            ->toArray();
    }

    public function changePassword(Request $request, EntityManager $entityManager, HashManager $hashManager)
    {
        $request->validate([
            'current_password' => ['required', 'string', 'min:8'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $entityManager->beginTransaction();
        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->find($request->user()->id);

        if (!$hashManager->check($request->get('current_password'), $user->getPassword())) {
            return response()->json(
                ['message'=> 'The given data was invalid', 'errors' => ['current_password' => 'Invalid password']],
                403
            );
        }

        $user->updatePassword($hashManager->make($request->get('password')));

        $entityManager->flush();
        $entityManager->commit();

        return '';
    }

    public function changeEmail(Request $request, EntityManager $entityManager, HashManager $hashManager)
    {
        $request->validate([
            'current_password' => ['required', 'string', 'min:8'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);

        $entityManager->beginTransaction();
        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->find($request->user()->id);

        if (!$hashManager->check($request->get('current_password'), $user->getPassword())) {
            return response()->json(
                ['message'=> 'The given data was invalid', 'errors' => ['current_password' => 'Invalid password']],
                403
            );
        }

        $user->updateEmail($request->get('email'));

        $entityManager->flush();
        $entityManager->commit();

        return '';
    }

    public function changeProfile(Request $request, EntityManager $entityManager)
    {
        $userId = $request->user()->id;
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($userId)],
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
        ]);

        $entityManager->beginTransaction();
        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->find($userId);

        $user->updateProfile(
            $request->get('name'),
            $request->get('firstname'),
            $request->get('lastname')
        );

        $entityManager->flush();
        $entityManager->commit();

        return '';
    }
}
