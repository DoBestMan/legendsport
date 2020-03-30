<?php
namespace App\Http\Controllers\App\Api;

use App\Http\Controllers\Controller;
use App\Models\PendingOdd;
use App\Models\Tournament;
use App\Models\TournamentEvent;
use App\Services\ParlayBetService;
use App\Services\PendingOddService;
use App\Tournament\NotEnoughBalanceException;
use App\Tournament\NotEnoughChipsException;
use App\Tournament\PendingOddType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class TournamentBetParlayController extends Controller
{
    public function post(
        Tournament $tournament,
        Request $request,
        ParlayBetService $parlayBetService,
        PendingOddService $pendingOddService
    ) {
        $request->validate([
            'pending_odds' => ['required', 'array', 'min:2'],
            'pending_odds.*.event_id' => ['required', 'numeric', Rule::exists(TournamentEvent::table(), "id")],
            'pending_odds.*.type' => ['required', Rule::in(array_values(PendingOddType::toArray()))],
            'wager' => ['required', 'numeric', 'min:1'],
        ]);

        $inputPendingOdds = $request->request->get("pending_odds");

        $tournamentEventsIds = collect($inputPendingOdds)
            ->map(fn(array $event) => $event["event_id"]);

        $tournamentEventsDict = TournamentEvent::findMany($tournamentEventsIds)->getDictionary();

        $pendingOdds = collect($inputPendingOdds)
            ->map(fn(array $pendingOdd) => new PendingOdd(
                new PendingOddType($pendingOdd["type"]),
                $tournamentEventsDict[$pendingOdd["event_id"]],
            ))
            ->all();

        $pendingOddService->assignOdds($pendingOdds);

        // TODO Do not allow betting on passed matches

        try {
            $parlayBet = $parlayBetService->bet(
                $tournament,
                $request->user(),
                $pendingOdds,
                $request->request->get("wager")
            );
        } catch (NotEnoughBalanceException $e) {
            return new JsonResponse(
                [
                    "message" => "You don't have enough balance. Top up!"
                ],
                Response::HTTP_BAD_REQUEST
            );
        } catch (NotEnoughChipsException $e) {
            return new JsonResponse(
                [
                    "message" => "You don't have enough chips."
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return [
            "id" => $parlayBet->id,
        ];
    }
}
