<?php
namespace Tests\Utils\Concerns;

use App\Betting\BettingProvider;
use Mockery;
use Mockery\MockInterface;

trait BettingProviderConcern
{
    /** @var BettingProvider|MockInterface */
    public $bettingProvider;

    public function mockBettingProvider()
    {
        $this->bettingProvider = Mockery::mock(BettingProvider::class);
        $this->app->instance(BettingProvider::class, $this->bettingProvider);
    }
}
