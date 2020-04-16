<?php
namespace App\Services;

use App\Betting\BettingProvider;
use App\Betting\SportEventOdd;
use App\Models\PendingOdd;
use App\Tournament\Enums\PendingOddType;
use Decimal\Decimal;
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
            $handicapValue = $this->getHandicapByType($sportEventOdd, $pendingOdd->getType());
            $pendingOdd->setOdd($oddValue);
            $pendingOdd->setHandicap($handicapValue);
        }
    }

    private function getOddByType(SportEventOdd $sportEventOdd, PendingOddType $type): int
    {
        switch ($type) {
            case PendingOddType::MONEY_LINE_HOME():
                return $sportEventOdd->getMoneyLineHome();

            case PendingOddType::MONEY_LINE_AWAY():
                return $sportEventOdd->getMoneyLineAway();

            case PendingOddType::SPREAD_HOME():
                return $sportEventOdd->getPointSpreadHome();

            case PendingOddType::SPREAD_AWAY():
                return $sportEventOdd->getPointSpreadAway();

            case PendingOddType::TOTAL_UNDER():
                return $sportEventOdd->getUnderLine();

            case PendingOddType::TOTAL_OVER():
                return $sportEventOdd->getOverLine();

            default:
                throw new UnexpectedValueException("Unexpected odd type");
        }
    }

    private function getHandicapByType(SportEventOdd $sportEventOdd, PendingOddType $type): ?Decimal
    {
        switch ($type) {
            case PendingOddType::MONEY_LINE_HOME():
            case PendingOddType::MONEY_LINE_AWAY():
                return null;

            case PendingOddType::SPREAD_HOME():
                return $sportEventOdd->getPointSpreadHomeLine();

            case PendingOddType::SPREAD_AWAY():
                return $sportEventOdd->getPointSpreadAwayLine();

            case PendingOddType::TOTAL_UNDER():
            case PendingOddType::TOTAL_OVER():
                return $sportEventOdd->getTotalNumber();

            default:
                throw new UnexpectedValueException("Unexpected odd type");
        }
    }
}
