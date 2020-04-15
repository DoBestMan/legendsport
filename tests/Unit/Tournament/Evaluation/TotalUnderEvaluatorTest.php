<?php
namespace Tests\Unit\Tournament\Evaluation;

use App\Models\ApiEvent;
use App\Tournament\BetStatus;
use App\Tournament\Evaluation\TotalUnderEvaluator;
use Decimal\Decimal;
use Tests\Utils\UnitTestCase;

class TotalUnderEvaluatorTest extends UnitTestCase
{
    /** @test */
    public function mark_as_win()
    {
        // given
        $apiEvent = new ApiEvent();
        $apiEvent->score_home = 21;
        $apiEvent->score_away = 20;

        // when
        $betStatus = (new TotalUnderEvaluator())->evaluate($apiEvent, new Decimal("42.5"));

        // then
        $this->assertSameEnum(BetStatus::WIN(), $betStatus);
    }

    /** @test */
    public function mark_as_loss()
    {
        // given
        $apiEvent = new ApiEvent();
        $apiEvent->score_home = 22;
        $apiEvent->score_away = 21;

        // when
        $betStatus = (new TotalUnderEvaluator())->evaluate($apiEvent, new Decimal("42.5"));

        // then
        $this->assertSameEnum(BetStatus::LOSS(), $betStatus);
    }

    /** @test */
    public function mark_as_push()
    {
        // given
        $apiEvent = new ApiEvent();
        $apiEvent->score_home = 27;
        $apiEvent->score_away = 24;

        // when
        $betStatus = (new TotalUnderEvaluator())->evaluate($apiEvent, new Decimal("51"));

        // then
        $this->assertSameEnum(BetStatus::PUSH(), $betStatus);
    }
}
