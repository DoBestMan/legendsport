<?php

namespace Tests\Utils;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use MyCLabs\Enum\Enum;
use Tests\Utils\Concerns\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    use CreatesApplication;

    public function assertSameEnum(Enum $expected, Enum $value)
    {
        $this->assertTrue($expected->equals($value), "$expected does not equal $value");
    }
}
