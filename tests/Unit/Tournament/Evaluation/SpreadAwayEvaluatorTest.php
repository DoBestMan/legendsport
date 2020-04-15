<?php
namespace Tests\Unit\Tournament\Evaluation;

use App\Models\ApiEvent;
use App\Tournament\BetStatus;
use App\Tournament\Evaluation\SpreadAwayEvaluator;
use Decimal\Decimal;
use Tests\Utils\UnitTestCase;

class SpreadAwayEvaluatorTest extends UnitTestCase
{
    /** @test */
    public function mark_as_win()
    {
        // given
        $apiEvent = new ApiEvent();
        $apiEvent->score_home = 7;
        $apiEvent->score_away = 5;

        // when
        $betStatus = (new SpreadAwayEvaluator())->evaluate($apiEvent, new Decimal("2.5"));

        // then
        $this->assertSameEnum(BetStatus::WIN(), $betStatus);
    }

    /** @test */
    public function mark_as_loss()
    {
        // given
        $apiEvent = new ApiEvent();
        $apiEvent->score_home = 6;
        $apiEvent->score_away = 4;

        // when
        $betStatus = (new SpreadAwayEvaluator())->evaluate($apiEvent, new Decimal("1"));

        // then
        $this->assertSameEnum(BetStatus::LOSS(), $betStatus);
    }

    /** @test */
    public function mark_as_push()
    {
        // given
        $apiEvent = new ApiEvent();
        $apiEvent->score_home = 6;
        $apiEvent->score_away = 9;

        // when
        $betStatus = (new SpreadAwayEvaluator())->evaluate($apiEvent, new Decimal("-3"));

        // then
        $this->assertSameEnum(BetStatus::PUSH(), $betStatus);
    }
}
