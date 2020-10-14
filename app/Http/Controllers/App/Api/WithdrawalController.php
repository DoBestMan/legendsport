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
use App\Mail\Withdrawal;
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
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class WithdrawalController extends Controller
{
    public function post(
        Request $request,
        Dispatcher $dispatcher,
        EntityManager $entityManager
    ) {
        $request->validate([
            'btcAddress' => ['required', 'alpha_num', 'between:26,35'],
            'amount' => ['required', 'numeric', 'min:50'],
        ]);

        $entityManager->beginTransaction();
        /** @var User $user */
        $user = $entityManager->find(User::class, $request->user()->id, LockMode::PESSIMISTIC_WRITE);
        try {
            $btcAddress = $request->request->get('btcAddress');
            $amount = (int) $request->request->get('amount') * 100;
            $user->makeWithdrawal($btcAddress, $amount);
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

        Mail::to($user->getEmail())->send(
            new Withdrawal('btc', $user->getFullname(), ['btcAddress' => $btcAddress, 'amount' => $amount])
        );
        $dispatcher->dispatch(new MeUpdate($request->user()));

        return [];
    }
}
