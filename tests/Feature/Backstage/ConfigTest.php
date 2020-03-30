<?php

namespace Tests\Feature\Backstage;

use App\Models\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Utils\TestCase;

class ConfigTest extends TestCase
{
    use RefreshDatabase;

    private function createConfig(array $attributes = [])
    {
        return factory(Config::class)->create($attributes);
    }

    public function test_backstage_config_show()
    {
        // given
        $config = $this->createConfig([
            'config' => json_encode([
                'chips' => 10000,
                'commission' => 2,
                'keep_completed' => 2,
            ])
        ]);

        // when
        $response = $this->get(route('config.show', $config->config));

        // then
        $response
            ->assertStatus(200)
            ->assertSee('Configuration')
            ->assertSee($config->config['chips']);
    }

    public function test_backstage_config_edit()
    {
        // given
        $config = $this->createConfig([
            'config' => json_encode([
                'chips' => 10000,
                'commission' => 2,
                'keep_completed' => 2,
            ]),
        ]);

        // when
        $response = $this->get(route('config.edit', $config->config));

        // then
        $response
            ->assertStatus(200)
            ->assertSee($config->config['chips']);
    }

    public function test_backstage_config_update()
    {
        // given
        $config = $this->createConfig();

        // when
        $response = $this->put(route('config.update', $config->config), [
            'config' => 1,
        ]);

        // then
        $response->assertNoContent();
        $this->assertDatabaseHas('config', [
            'config' => 1,
        ]);
    }
}
