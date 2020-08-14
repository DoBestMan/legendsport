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
use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Models\TournamentEvent;
use App\Tournament\Enums\PendingOddType;
use App\Tournament\Events\TournamentUpdate;
use App\User\MeUpdate;
use Doctrine\ORM\EntityManager;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class TournamentBetStraightController extends Controller
{
    public function post(
        Tournament $tournament,
        Request $request,
        Dispatcher $dispatcher,
        EntityManager $entityManager
    ) {
        $request->validate([
            'pending_odds' => ['required', 'array', 'min:1'],
            'pending_odds.*.event_id' => [
                'required',
                'numeric',
                Rule::exists(TournamentEvent::table(), "id"),
            ],
            'pending_odds.*.type' => [
                'required',
                Rule::in(array_values(PendingOddType::toArray())),
            ],
            'pending_odds.*.wager' => ['required', 'numeric', 'min:100'],
        ]);

        $entityManager->beginTransaction();
        /** @var User $user */
        $user = $entityManager->find(User::class, $request->user()->id);
        /** @var \App\Domain\Tournament $tournamentEntity */
        $tournamentEntity = $entityManager->find(\App\Domain\Tournament::class, $tournament->id);
        $tournamentPlayer = $user->getTournamentPlayer($tournamentEntity);
        try {
            if ($tournamentPlayer === null) {
                throw BetPlacementException::notRegistered();
            }

            foreach ($request->request->get('pending_odds') as $pendingWager) {
                $tournamentEvent = $tournamentEntity->getEvent($pendingWager['event_id']);
                if ($tournamentEvent === null) {
                    throw BetPlacementException::invalidEvent();
                }

                $betItem = BetItem::createFromBetTypeAlias($pendingWager['type'], $tournamentEvent);
                $tournamentEntity->placeStraightBet($tournamentPlayer, (int)$pendingWager['wager'], $betItem);
            }
        } catch (BetPlacementException $e) {
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

        $dispatcher->dispatch(new TournamentUpdate($tournament));
        $dispatcher->dispatch(new MeUpdate($request->user()));

        return [];
    }
}
