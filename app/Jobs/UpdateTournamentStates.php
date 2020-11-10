<?php
namespace App\Jobs;

use App\Models\Tournament;
use App\Tournament\Enums\TournamentState;
use App\Tournament\Events\TournamentUpdate;
use Carbon\Carbon;
use Illuminate\Contracts\Events\Dispatcher;

final class UpdateTournamentStates
{
    public function handle(Dispatcher $dispatcher)
    {
        $query = Tournament::where('state', TournamentState::REGISTERING())
            ->where('registration_deadline', '<', Carbon::now());

        foreach ($query->cursor() as $tournament) {
            /** @var Tournament $tournament */
            if ($tournament->late_register === 1) {
                $tournament->state = TournamentState::LATE_REGISTERING();
            } else {
                $tournament->state = TournamentState::RUNNING();
            }
            $tournament->save();
            $dispatcher->dispatch(new TournamentUpdate($tournament));
        }

        $query = Tournament::where('state', TournamentState::LATE_REGISTERING())
            ->where('late_registration_deadline', '<', Carbon::now());

        foreach ($query->cursor() as $tournament) {
            /** @var Tournament $tournament */
            $tournament->state = TournamentState::RUNNING();
            $tournament->save();
            $dispatcher->dispatch(new TournamentUpdate($tournament));
        }
    }
}
