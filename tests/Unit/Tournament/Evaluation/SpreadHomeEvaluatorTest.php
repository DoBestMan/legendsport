<?php
namespace Tests\Unit\Tournament\Evaluation;

use App\Models\ApiEvent;
use App\Tournament\Enums\BetStatus;
use App\Tournament\Evaluation\SpreadHomeEvaluator;
use Decimal\Decimal;
use Tests\Utils\UnitTestCase;

class SpreadHomeEvaluatorTest extends UnitTestCase
{
    /** @test */
    public function mark_as_win()
    {
        // given
        $apiEvent = new ApiEvent();
        $apiEvent->score_home = 5;
        $apiEvent->score_away = 7;

        // when
        $betStatus = (new SpreadHomeEvaluator())->evaluate($apiEvent, new Decimal("2.5"));

        // then
        $this->assertSameEnum(BetStatus::WIN(), $betStatus);
    }

    /** @test */
    public function mark_as_loss()
    {
        // given
        $apiEvent = new ApiEvent();
        $apiEvent->score_home = 4;
        $apiEvent->score_away = 6;

        // when
        $betStatus = (new SpreadHomeEvaluator())->evaluate($apiEvent, new Decimal("1"));

        // then
        $this->assertSameEnum(BetStatus::LOSS(), $betStatus);
    }

    /** @test */
    public function mark_as_push()
    {
        // given
        $apiEvent = new ApiEvent();
        $apiEvent->score_home = 9;
        $apiEvent->score_away = 6;

        // when
        $betStatus = (new SpreadHomeEvaluator())->evaluate($apiEvent, new Decimal("-3"));

        // then
        $this->assertSameEnum(BetStatus::PUSH(), $betStatus);
    }
}
