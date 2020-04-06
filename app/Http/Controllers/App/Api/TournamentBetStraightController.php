<?php
namespace App\Http\Controllers\App\Api;

use App\Http\Controllers\Controller;
use App\Models\PendingOdd;
use App\Models\Tournament;
use App\Models\TournamentBet;
use App\Models\TournamentEvent;
use App\Services\PendingOddService;
use App\Tournament\Events\TournamentUpdate;
use App\Tournament\NotEnoughBalanceException;
use App\Tournament\NotEnoughChipsException;
use App\Tournament\PendingOddType;
use App\Tournament\StraightBetService;
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
        StraightBetService $straightBetService,
        PendingOddService $pendingOddService,
        Dispatcher $dispatcher
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

        $inputPendingOdds = $request->request->get("pending_odds");

        $tournamentEventsIds = collect($inputPendingOdds)->map(
            fn(array $event) => $event["event_id"],
        );
        $tournamentEventsDict = TournamentEvent::findMany($tournamentEventsIds)->getDictionary();

        $pendingOdds = collect($inputPendingOdds)
            ->map(
                fn(array $pendingOdd) => new PendingOdd(
                    new PendingOddType($pendingOdd["type"]),
                    $tournamentEventsDict[$pendingOdd["event_id"]],
                    (int) $pendingOdd["wager"],
                ),
            )
            ->all();

        $pendingOddService->assignOdds($pendingOdds);

        // TODO Do not allow betting on passed matches

        try {
            $tournamentBets = $straightBetService->bet($tournament, $request->user(), $pendingOdds);
        } catch (NotEnoughBalanceException $e) {
            return new JsonResponse(
                [
                    "message" => "You don't have enough balance. Top up!",
                ],
                Response::HTTP_BAD_REQUEST,
            );
        } catch (NotEnoughChipsException $e) {
            return new JsonResponse(
                [
                    "message" => "You don't have enough chips.",
                ],
                Response::HTTP_BAD_REQUEST,
            );
        }

        $dispatcher->dispatch(new TournamentUpdate($tournament));

        return collect($tournamentBets)->map(fn(TournamentBet $bet) => $bet->id);
    }
}
