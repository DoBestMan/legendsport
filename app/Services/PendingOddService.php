<?php
namespace App\Services;

use App\Models\PendingOdd;
use App\Tournament\PendingOddType;
use UnexpectedValueException;

class PendingOddService
{
    private OddService $oddService;

    public function __construct(OddService $oddService)
    {
        $this->oddService = $oddService;
    }

    /**
     * @param PendingOdd[] $pendingOdds
     */
    public function assignOdds(array $pendingOdds)
    {
        $oddDict = collect($this->oddService->getOdds())->flatMap(
            fn(array $event) => [
                $event["Odds"][0]["EventID"] => $event["Odds"][0],
            ],
        );

        foreach ($pendingOdds as $pendingOdd) {
            $tournamentEvent = $pendingOdd->getTournamentEvent();
            $odds = $oddDict->get($tournamentEvent->apiEvent->api_id);
            $oddValue = $this->getOddByType($odds, $pendingOdd->getType());
            $pendingOdd->setOdd($oddValue);
        }
    }

    private function getOddByType(array $odds, PendingOddType $type): int
    {
        switch ($type) {
            case PendingOddType::MONEY_LINE_HOME():
                return floatval($odds["MoneyLineHome"]);

            case PendingOddType::MONEY_LINE_AWAY():
                return floatval($odds["MoneyLineAway"]);

            case PendingOddType::SPREAD_HOME():
                return floatval($odds["SpreadHome"]);

            case PendingOddType::SPREAD_AWAY():
                return floatval($odds["SpreadAway"]);

            case PendingOddType::TOTAL_UNDER():
                return floatval($odds["UnderLine"]);

            case PendingOddType::TOTAL_OVER():
                return floatval($odds["OverLine"]);

            default:
                throw new UnexpectedValueException("Unexpected odd type");
        }
    }
}
