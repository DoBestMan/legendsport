<?php

namespace Tests\Feature\Intra;

use Tests\TestCase;
use App\Models\Backstage\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConfigTest extends TestCase
{
    use RefreshDatabase;

    private function create_config(Array $attributes = [])
    {           
        return factory(Config::class)->create($attributes);
    }

    public function test_backstage_config_show()
    {
        $config = $this->create_config([
            'chips' => 1,
        ]);

        $this->get(route('config.show', $config))
            ->assertStatus(200)
            ->assertSee('Show config')
            ->assertSee('chips');
    }

    public function test_backstage_config_edit()
    {
        $config = $this->create_config([
            'chips' => 1,
        ]);

        $this->get(route('config.edit', $config))
            ->assertStatus(200)
            ->assertSee('chips');
    }

    public function test_backstage_config_update()
    {
        $config = $this->create_config();

        $this->put(route('config.update', $config), [
                'commission'=> 1,
                'chips'=> 1,
            ])->assertRedirect(route('config.edit'));

        $this->assertDatabaseHas('config', [
            'chips' => 1,
        ]);
    }
}
