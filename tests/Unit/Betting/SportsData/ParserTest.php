<?php

namespace Unit\Betting\SportsData;

use App\Betting\SportEventOdd;
use App\Betting\SportsData\Parser;
use Decimal\Decimal;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public function testParseMainLines()
    {
        $data = [
            [
                "AnyBetsAvailable"=> true,
                "AvailableSportsbooks"=> [
                    [
                        "Name"=> "DraftKings",
                        "SportsbookID"=> 7
                    ]
                ],
                "BettingBetTypeID"=> 1,
                "BettingEventID"=> 285,
                "BettingMarketTypeID"=> 1,
                "BettingOutcomes"=> [
                    [
                        "BettingOutcomeTypeID"=> 1,
                        "PayoutAmerican"=> -200,
                        "SportsBook"=> [
                            "Name"=> "DraftKings",
                            "SportsbookID"=> 7
                        ],
                        "Value"=> null
                    ],
                    [
                        "BettingOutcomeTypeID"=> 2,
                        "PayoutAmerican"=> 150,
                        "SportsBook"=> [
                            "Name"=> "DraftKings",
                            "SportsbookID"=> 7
                        ],
                        "Value"=> null
                    ],
                ],
                "BettingPeriodTypeID"=> 1,
            ],
            [
                "AnyBetsAvailable"=> true,
                "AvailableSportsbooks"=> [
                    [
                        "Name"=> "DraftKings",
                        "SportsbookID"=> 7
                    ]
                ],
                "BettingBetTypeID"=> 2,
                "BettingEventID"=> 285,
                "BettingMarketTypeID"=> 1,
                "BettingOutcomes"=> [
                    [
                        "BettingOutcomeTypeID"=> 1,
                        "PayoutAmerican"=> 100,
                        "SportsBook"=> [
                            "Name"=> "DraftKings",
                            "SportsbookID"=> 7
                        ],
                        "Value"=> 3.0
                    ],
                    [
                        "BettingOutcomeTypeID"=> 2,
                        "PayoutAmerican"=> -115,
                        "SportsBook"=> [
                            "Name"=> "DraftKings",
                            "SportsbookID"=> 7
                        ],
                        "Value"=> -3.0
                    ],
                ],
                "BettingPeriodTypeID"=> 1,
            ],
            [
                "AnyBetsAvailable"=> true,
                "AvailableSportsbooks"=> [
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
                        "PayoutAmerican"=> 115,
                        "SportsBook"=> [
                            "Name"=> "DraftKings",
                            "SportsbookID"=> 7
                        ],
                        "Value"=> 8.0
                    ],
                ],
                "BettingPeriodTypeID"=> 1,
            ],
        ];

        $sut = new Parser();
        $result = $sut->parseMainLines($data);

        $expected = new SportEventOdd(
            285,
            -200,
            150,
            100,
            -115,
            new Decimal('3.0'),
            new Decimal('-3.0'),
            115,
            -106,
            new Decimal('8.0')
        );

        self::assertEquals($expected, $result);
        self::assertEquals('3', $result->getPointSpreadHomeLine()->toString());
        self::assertEquals('-3', $result->getPointSpreadAwayLine()->toString());
        self::assertEquals('8', $result->getTotalNumber()->toString());
    }
}
