<?php

namespace Tests\Utils;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Event;
use MyCLabs\Enum\Enum;
use Tests\Utils\Concerns\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
    }

    public function actingAsAdmin(Authenticatable $user)
    {
        $this->actingAs($user, "backstage");
    }

    public function assertSameEnum(Enum $expected, Enum $value)
    {
        $this->assertTrue($expected->equals($value), "$expected does not equal $value");
    }
}
