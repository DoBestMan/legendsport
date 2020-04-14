<?php

namespace Tests\Utils;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Event;
use Tests\Utils\Concerns\CreatesApplication;
use Tests\Utils\Concerns\EnumConcerns;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    use CreatesApplication;
    use EnumConcerns;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
    }

    public function actingAsAdmin(Authenticatable $user)
    {
        $this->actingAs($user, "backstage");
    }
}
