<?php
namespace Tests\Utils\Concerns;

use App\Betting\JsonOddApiService;
use Mockery;
use Mockery\MockInterface;

trait JsonOddApiServiceConcern
{
    /** @var JsonOddApiService|MockInterface */
    public $jsonOddApiServiceMock;

    public function mockJsonOddApiService()
    {
        $this->jsonOddApiServiceMock = Mockery::mock(JsonOddApiService::class);
        $this->app->instance(JsonOddApiService::class, $this->jsonOddApiServiceMock);
    }
}
