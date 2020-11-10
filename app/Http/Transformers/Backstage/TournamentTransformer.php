<?php

namespace App\Http\Transformers\Backstage;

use App\Domain\Tournament;
use League\Fractal\TransformerAbstract;

class TournamentTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'apiSelectedSports'
    ];
    public function transform(Tournament $tournament)
    {
        return [
            'buyIn' => $tournament->getBuyIn(),
            'chips' => $tournament->getChips(),
            'commission' => $tournament->getCommission(),
            'config' => '',
            'interval' => $tournament->getLateRegisterRule()['interval'] ?? '',
            'value' => $tournament->getLateRegisterRule()['value'] ?? '',
            'lateRegister' => $tournament->getLateRegister(),
            'name' => $tournament->getName(),
            'playersLimit' => $tournament->getPlayersLimit(),
            'prizePool' => $tournament->getPrizePool()['type'],
            'prizePoolValue' => $tournament->getPrizePool()['fixed_value'],
            'state' => $tournament->getState(),
            'timeFrame' => $tournament->getTimeFrame(),
            'minBots' => $tournament->getBots()['min'],
            'maxBots' => $tournament->getBots()['max'],
            'addBots' => $tournament->getBots()['add'],
            'playerBots' => $tournament->getBots()['player'],
            'autoEnd' => $tournament->shouldAutoEnd(),
            'liveLines' => $tournament->hasLiveLines(),
        ];
    }

    public function includeApiSelectedSports(Tournament $tournament)
    {
        return $this->collection($tournament->getEvents(), new TournamentEventTransformer());
    }
}
