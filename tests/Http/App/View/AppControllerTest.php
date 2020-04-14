<?php
namespace Tests\Http\App\View;

use Tests\Utils\TestCase;

class AppControllerTest extends TestCase
{
    /** @test */
    public function renders_index_page()
    {
        // when
        $response = $this->get("http://legendsports.local/");

        // then
        $response->assertOk();
        $response->assertSee('<div id="main"', false);
    }
}
