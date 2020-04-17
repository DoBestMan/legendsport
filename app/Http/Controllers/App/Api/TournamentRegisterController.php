<?php
namespace App\Http\Controllers\App\Api;

use App\Models\Tournament;
use App\Services\TournamentPlayerService;
use App\Tournament\Events\TournamentUpdate;
use App\Tournament\Exceptions\AlreadyRegisteredException;
use App\Tournament\Exceptions\NotEnoughBalanceException;
use App\Tournament\Exceptions\RegistrationProhibitedException;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TournamentRegisterController
{
    public function post(
        Tournament $tournament,
        Request $request,
        TournamentPlayerService $tournamentPlayerService,
        Dispatcher $dispatcher
    ) {
        try {
            $player = $tournamentPlayerService->register($tournament, $request->user());
        } catch (AlreadyRegisteredException $e) {
            return new JsonResponse(
                [
                    "message" => "You're already registered.",
                ],
                Response::HTTP_BAD_REQUEST,
            );
        } catch (NotEnoughBalanceException $e) {
            return new JsonResponse(
                [
                    "message" => "You don't have enough balance. Top up!",
                ],
                Response::HTTP_BAD_REQUEST,
            );
        } catch (RegistrationProhibitedException $e) {
            return new JsonResponse(
                [
                    "message" => "You cannot register for this tournament.",
                ],
                Response::HTTP_BAD_REQUEST,
            );
        }

        $dispatcher->dispatch(new TournamentUpdate($tournament));

        return new JsonResponse(["id" => $player->id], Response::HTTP_CREATED);
    }
}
