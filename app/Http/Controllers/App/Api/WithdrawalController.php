<?php
namespace App\Http\Controllers\App\Api;

use App\Domain\BetItem;
use App\Domain\BetPlacementException;
use App\Domain\BetTypes\MoneyLineAway;
use App\Domain\BetTypes\MoneyLineHome;
use App\Domain\BetTypes\SpreadAway;
use App\Domain\BetTypes\SpreadHome;
use App\Domain\BetTypes\TotalOver;
use App\Domain\BetTypes\TotalUnder;
use App\Domain\User;
use App\Domain\UserBalanceException;
use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Models\TournamentEvent;
use App\Tournament\Enums\PendingOddType;
use App\Tournament\Events\TournamentUpdate;
use App\User\MeUpdate;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManager;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class WithdrawalController extends Controller
{
    public function post(
        Request $request,
        Dispatcher $dispatcher,
        EntityManager $entityManager
    ) {
        $request->validate([
            'btcAddress' => ['required'],
            'amount' => ['required'],
        ]);

        $entityManager->beginTransaction();
        /** @var User $user */
        $user = $entityManager->find(User::class, $request->user()->id);
        try {
            $user->makeWithdrawal($request->request->get('btcAddress'), $request->request->get('amount') * 100);
        } catch (UserBalanceException $e) {
            $entityManager->rollback();
            return new JsonResponse(
                [
                    'message' => $e->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST,
            );
        }

        $entityManager->flush();
        $entityManager->commit();

        $dispatcher->dispatch(new MeUpdate($request->user()));

        return [];
    }
}
