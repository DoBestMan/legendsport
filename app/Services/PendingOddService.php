<?php
namespace App\Services;

use App\Betting\BettingProvider;
use App\Betting\SportEventOdd;
use App\Models\PendingOdd;
use App\Tournament\PendingOddType;
use UnexpectedValueException;

class PendingOddService
{
    private BettingProvider $betProvider;

    public function __construct(BettingProvider $betProvider)
    {
        $this->betProvider = $betProvider;
    }

    /**
     * @param PendingOdd[] $pendingOdds
     */
    public function assignOdds(array $pendingOdds)
    {
        $oddDict = collect($this->betProvider->getOdds())->mapWithKeys(
            fn(SportEventOdd $sportEventOdd) => [
                $sportEventOdd->getExternalEventId() => $sportEventOdd,
            ],
        );

        foreach ($pendingOdds as $pendingOdd) {
            $tournamentEvent = $pendingOdd->getTournamentEvent();
            $sportEventOdd = $oddDict->get($tournamentEvent->apiEvent->api_id);
            $oddValue = $this->getOddByType($sportEventOdd, $pendingOdd->getType());
            $pendingOdd->setOdd($oddValue);
        }
    }

    private function getOddByType(SportEventOdd $sportEventOdd, PendingOddType $type): int
    {
        switch ($type) {
            case PendingOddType::MONEY_LINE_HOME():
                return floatval($sportEventOdd->getMoneyLineHome());

            case PendingOddType::MONEY_LINE_AWAY():
                return floatval($sportEventOdd->getMoneyLineAway());

            case PendingOddType::SPREAD_HOME():
                return floatval($sportEventOdd->getPointSpreadHome());

            case PendingOddType::SPREAD_AWAY():
                return floatval($sportEventOdd->getPointSpreadAway());

            case PendingOddType::TOTAL_UNDER():
                return floatval($sportEventOdd->getUnderLine());

            case PendingOddType::TOTAL_OVER():
                return floatval($sportEventOdd->getOverLine());

            default:
                throw new UnexpectedValueException("Unexpected odd type");
        }
    }
}
