<?php
namespace Tests\Unit\Tournament;

use App\Tournament\Enums\BetStatus;
use App\Tournament\BetStatusCalculator;
use Tests\Utils\UnitTestCase;

class BetStatusCalculatorTest extends UnitTestCase
{
    /** @test */
    public function win_if_each_is_win_ignoring_push()
    {
        // given
        $betStatuses = [BetStatus::WIN(), BetStatus::WIN(), BetStatus::PUSH()];

        // when
        $status = (new BetStatusCalculator($betStatuses))->calculate();

        // then
        $this->assertSameEnum(BetStatus::WIN(), $status);
    }

    /** @test */
    public function loss_if_at_least_one_is_lost_and_none_is_pending()
    {
        // given
        $betStatuses = [BetStatus::LOSS(), BetStatus::WIN(), BetStatus::PUSH()];

        // when
        $status = (new BetStatusCalculator($betStatuses))->calculate();

        // then
        $this->assertSameEnum(BetStatus::LOSS(), $status);
    }

    /** @test */
    public function pending_if_at_least_one_is_pending()
    {
        // given
        $betStatuses = [
            BetStatus::LOSS(),
            BetStatus::PENDING(),
            BetStatus::WIN(),
            BetStatus::PUSH(),
        ];

        // when
        $status = (new BetStatusCalculator($betStatuses))->calculate();

        // then
        $this->assertSameEnum(BetStatus::PENDING(), $status);
    }

    /** @test */
    public function push_if_each_is_push()
    {
        // given
        $betStatuses = [BetStatus::PUSH(), BetStatus::PUSH()];

        // when
        $status = (new BetStatusCalculator($betStatuses))->calculate();

        // then
        $this->assertSameEnum(BetStatus::PUSH(), $status);
    }
}
