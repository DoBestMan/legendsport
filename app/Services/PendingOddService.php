<?php
namespace App\Services;

use App\Models\PendingOdd;
use App\SportEvent\OddsProvider;
use App\SportEvent\SportEventOdd;
use App\Tournament\PendingOddType;
use UnexpectedValueException;

class PendingOddService
{
    private OddsProvider $oddsProvider;

    public function __construct(OddsProvider $oddsProvider)
    {
        $this->oddsProvider = $oddsProvider;
    }

    /**
     * @param PendingOdd[] $pendingOdds
     */
    public function assignOdds(array $pendingOdds)
    {
        $oddDict = collect($this->oddsProvider->getOdds())->flatMap(
            fn(SportEventOdd $sportEventOdd) => [
                $sportEventOdd->getExternalEventId() => $sportEventOdd,
            ],
        );

        foreach ($pendingOdds as $pendingOdd) {
            $tournamentEvent = $pendingOdd->getTournamentEvent();
            $odds = $oddDict->get($tournamentEvent->apiEvent->api_id);
            $oddValue = $this->getOddByType($odds, $pendingOdd->getType());
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
