<?php
namespace Tests\Unit\Tournament\Evaluation;

use App\Models\ApiEvent;
use App\Tournament\Enums\BetStatus;
use App\Tournament\Evaluation\MoneyLineHomeEvaluator;
use Tests\Utils\UnitTestCase;

class MoneyLineHomeEvaluatorTest extends UnitTestCase
{
    /** @test */
    public function mark_as_win()
    {
        // given
        $apiEvent = new ApiEvent();
        $apiEvent->score_home = 6;
        $apiEvent->score_away = 5;

        // when
        $betStatus = (new MoneyLineHomeEvaluator())->evaluate($apiEvent, null);

        // then
        $this->assertSameEnum(BetStatus::WIN(), $betStatus);
    }

    /** @test */
    public function mark_as_loss()
    {
        // given
        $apiEvent = new ApiEvent();
        $apiEvent->score_home = 5;
        $apiEvent->score_away = 6;

        // when
        $betStatus = (new MoneyLineHomeEvaluator())->evaluate($apiEvent, null);

        // then
        $this->assertSameEnum(BetStatus::LOSS(), $betStatus);
    }

    /** @test */
    public function mark_as_push()
    {
        // given
        $apiEvent = new ApiEvent();
        $apiEvent->score_home = 6;
        $apiEvent->score_away = 6;

        // when
        $betStatus = (new MoneyLineHomeEvaluator())->evaluate($apiEvent, null);

        // then
        $this->assertSameEnum(BetStatus::PUSH(), $betStatus);
    }
}
