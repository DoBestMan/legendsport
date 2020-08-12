<?php

namespace Unit\Betting\SportsData\OddsFilters;

use App\Betting\SportsData\OddsFilters\HasOddsFromChosenSportsbook;
use PHPUnit\Framework\TestCase;

class HasOddsFromChosenSportsbookTest extends TestCase
{
    public function testIterate()
    {
        $data = [
            [
                "AnyBetsAvailable"=> true,
                "AvailableSportsbooks"=> [
                    [
                        "Name"=> "PointsBet",
                        "SportsbookID"=> 23
                    ],
                    [
                        "Name"=> "DraftKings",
                        "SportsbookID"=> 7
                    ]
                ],
                "BettingBetTypeID"=> 3,
                "BettingEventID"=> 285,
                "BettingMarketTypeID"=> 1,
                "BettingOutcomes"=> [
                    [
                        "BettingOutcomeTypeID"=> 3,
                        "PayoutAmerican"=> -105,
                        "SportsBook"=> [
                            "Name"=> "PointsBet",
                            "SportsbookID"=> 23
                        ],
                        "Value"=> 8.5
                    ],
                    [
                        "BettingOutcomeTypeID"=> 4,
                        "PayoutAmerican"=> -115,
                        "SportsBook"=> [
                            "Name"=> "PointsBet",
                            "SportsbookID"=> 23
                        ],
                        "Value"=> 8.5
                    ],
                    [
                        "BettingOutcomeTypeID"=> 4,
                        "PayoutAmerican"=> -106,
                        "SportsBook"=> [
                            "Name"=> "DraftKings",
                            "SportsbookID"=> 7
                        ],
                        "Value"=> 8.0
                    ],
                    [
                        "BettingOutcomeTypeID"=> 3,
                        "PayoutAmerican"=> -115,
                        "SportsBook"=> [
                            "Name"=> "DraftKings",
                            "SportsbookID"=> 7
                        ],
                        "Value"=> 8.0
                    ],
                ],
                "BettingPeriodTypeID"=> 1,
            ],
            [
                "AnyBetsAvailable"=> true,
                "AvailableSportsbooks"=> [
                    [
                        "Name"=> "PointsBet",
                        "SportsbookID"=> 23
                    ],
                ],
                "BettingBetTypeID"=> 3,
                "BettingEventID"=> 285,
                "BettingMarketTypeID"=> 1,
                "BettingOutcomes"=> [
                    [
                        "BettingOutcomeTypeID"=> 3,
                        "PayoutAmerican"=> -105,
                        "SportsBook"=> [
                            "Name"=> "PointsBet",
                            "SportsbookID"=> 23
                        ],
                        "Value"=> 8.5
                    ],
                    [
                        "BettingOutcomeTypeID"=> 4,
                        "PayoutAmerican"=> -115,
                        "SportsBook"=> [
                            "Name"=> "PointsBet",
                            "SportsbookID"=> 23
                        ],
                        "Value"=> 8.5
                    ],
                ],
                "BettingPeriodTypeID"=> 1,
            ],
            [
                "AnyBetsAvailable"=> false,
                "AvailableSportsbooks"=> [
                    [
                        "Name"=> "PointsBet",
                        "SportsbookID"=> 23
                    ],
                    [
                        "Name"=> "DraftKings",
                        "SportsbookID"=> 7
                    ]
                ],
                "BettingBetTypeID"=> 3,
                "BettingEventID"=> 285,
                "BettingMarketTypeID"=> 1,
                "BettingOutcomes"=> [],
                "BettingPeriodTypeID"=> 1,
            ],
        ];
        $sut = new HasOddsFromChosenSportsbook(new \ArrayIterator($data));

        $result = iterator_to_array($sut);
        self::assertCount(1, $result);
        self::assertCount(2, current($result)['BettingOutcomes']);
    }
}
