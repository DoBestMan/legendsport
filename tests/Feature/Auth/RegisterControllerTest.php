<?php
namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\Utils\TestCase;

class RegisterControllerTest extends TestCase
{
    /** @test */
    public function registers_a_user()
    {
        // when
        $response = $this->postJson("http://legendsports.local/register", [
            "email" => "example@example.pl",
            "name" => "My Example",
            "password" => "abc12345",
            "password_confirmation" => "abc12345",
        ]);

        // then
        $response->assertCreated();
        $this->assertDatabaseHas(User::table(), [
            "email" => "example@example.pl",
            "name" => "My Example",
            "balance" => 1000000,
        ]);
    }
}
