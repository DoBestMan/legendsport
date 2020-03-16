<?php
namespace App\Http\Controllers\App\Api;

use App\Http\Controllers\Controller;
use App\Models\Backstage\Tournament;
use App\Models\Backstage\TournamentEvent;
use App\Models\Backstage\TournamentSport;

class TournamentCollection extends Controller
{
    public function get()
    {
        return Tournament::with(['events', 'events.apiEvent', 'sports'])
            ->get()
            ->map(function (Tournament $tournament) {
                $startDate = collect($tournament->events)
                    ->filter(
                        fn(TournamentEvent $event) => array_key_exists(
                            'MatchTime',
                            $event->apiEvent->api_data,
                        ),
                    )
                    ->map(fn(TournamentEvent $event) => $event->apiEvent->api_data['MatchTime'])
                    ->min();

                $sportIds = $tournament->sports
                    ->map(fn(TournamentSport $tournamentSport) => $tournamentSport->sport_id)
                    ->all();

                $tournament = array_merge($tournament->toArray(), [
                    'enrolled' => 0,
                    'sport_id' => $sportIds,
                    'starts' => $startDate,
                ]);

                return $tournament;
            });
    }
}
