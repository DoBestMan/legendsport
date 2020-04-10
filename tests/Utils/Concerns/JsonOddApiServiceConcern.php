<?php
namespace Tests\Utils\Concerns;

use App\Betting\JsonOddAPI;
use Mockery;
use Mockery\MockInterface;

trait JsonOddApiServiceConcern
{
    /** @var JsonOddAPI|MockInterface */
    public $jsonOddApiServiceMock;

    public function mockJsonOddApiService()
    {
        $this->jsonOddApiServiceMock = Mockery::mock(JsonOddAPI::class);
        $this->app->instance(JsonOddAPI::class, $this->jsonOddApiServiceMock);
    }
}
