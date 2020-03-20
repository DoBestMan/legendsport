<?php

namespace Tests\Feature\Intra;

use Tests\TestCase;
use App\Models\Config;
use App\Models\Tournament;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TournamentsTest extends TestCase
{
    use RefreshDatabase;

    private function create_Tournament(Array $attributes = [])
    {
        return factory(Tournament::class)->create($attributes);
    }

    public function test_backstage_tournaments_index()
    {
        $tournament = $this->create_Tournament([
            'name' => 'name',
        ]);

        $this->get(route('tournaments.index'))
            ->assertStatus(200)
            ->assertSee('name');
    }

    public function test_backstage_tournaments_create()
    {
        $config = factory(Config::class)->times(1)->create();

        $this->get(route('tournaments.create'))
            ->assertStatus(200)
            ->assertSee('Save');
    }

    public function test_backstage_tournaments_create_validate_name_required()
    {
        $this->from(route('tournaments.create'))
            ->post(route('tournaments.store'), [
                'avatar' => 1,
                'name'=> '',
                'type'=> 1,
                'prize_pool'=> 1,
                'players_limit'=> 1,
                'buy_in'=> 1,
                'chips'=> 1,
                'commission'=> 1,
                'late_register'=> 1,
                'late_register_rule'=> 1,
                'state'=> 1,
                'prizes'=> 1,
            ])
            ->assertRedirect(route('tournaments.create'))
            ->assertSessionHasErrors(['name']);

        $this->assertDatabaseMissing('tournaments', [
            'name' => '',
        ]);
    }

    public function test_backstage_tournaments_create_validate_type_required()
    {
        $this->from(route('tournaments.create'))
            ->post(route('tournaments.store'), [
                'avatar' => 1,
                'name'=> 'name',
                'type'=> '',
                'prize_pool'=> 1,
                'players_limit'=> 1,
                'buy_in'=> 1,
                'chips'=> 1,
                'commission'=> 1,
                'late_register'=> 1,
                'late_register_rule'=> 1,
                'state'=> 1,
                'prizes'=> 1,
            ])
            ->assertRedirect(route('tournaments.create'))
            ->assertSessionHasErrors(['type']);

        $this->assertDatabaseMissing('tournaments', [
            'type' => '',
        ]);
    }

    public function test_backstage_tournaments_create_validate_players_limit_required()
    {
        $this->from(route('tournaments.create'))
            ->post(route('tournaments.store'), [
                'avatar' => 1,
                'name'=> 'name',
                'type'=> 1,
                'prize_pool'=> 1,
                'players_limit'=> '',
                'buy_in'=> 1,
                'chips'=> 1,
                'commission'=> 1,
                'late_register'=> 1,
                'late_register_rule'=> 1,
                'state'=> 1,
                'prizes'=> 1,
            ])
            ->assertRedirect(route('tournaments.create'))
            ->assertSessionHasErrors(['players_limit']);

        $this->assertDatabaseMissing('tournaments', [
            'players_limit' => '',
        ]);
    }

    public function test_backstage_tournaments_create_validate_buy_in_required()
    {
        $this->from(route('tournaments.create'))
            ->post(route('tournaments.store'), [
                'avatar' => 1,
                'name'=> 'name',
                'type'=> 1,
                'prize_pool'=> 1,
                'players_limit'=> 1,
                'buy_in'=> '',
                'chips'=> 1,
                'commission'=> 1,
                'late_register'=> 1,
                'late_register_rule'=> 1,
                'state'=> 1,
                'prizes'=> 1,
            ])
            ->assertRedirect(route('tournaments.create'))
            ->assertSessionHasErrors(['buy_in']);

        $this->assertDatabaseMissing('tournaments', [
            'buy_in' => '',
        ]);
    }

    public function test_backstage_tournaments_create_validate_commission_required()
    {
        $this->from(route('tournaments.create'))
            ->post(route('tournaments.store'), [
                'avatar' => 1,
                'name'=> 'name',
                'type'=> 1,
                'prize_pool'=> 1,
                'players_limit'=> 1,
                'buy_in'=> 1,
                'commission'=> '',
                'chips'=> 1,
                'late_register'=> 1,
                'late_register_rule'=> 1,
                'state'=> 1,
                'prizes'=> 1,
            ])
            ->assertRedirect(route('tournaments.create'))
            ->assertSessionHasErrors(['commission']);

        $this->assertDatabaseMissing('tournaments', [
            'commission' => '',
        ]);
    }

    public function test_backstage_tournaments_create_validate_chips_required()
    {
        $this->from(route('tournaments.create'))
            ->post(route('tournaments.store'), [
                'avatar' => 1,
                'name'=> 'name',
                'type'=> 1,
                'prize_pool'=> 1,
                'players_limit'=> 1,
                'buy_in'=> 1,
                'commission'=> 1,
                'chips'=> '',
                'late_register'=> 1,
                'late_register_rule'=> 1,
                'state'=> 1,
                'prizes'=> 1,
            ])
            ->assertRedirect(route('tournaments.create'))
            ->assertSessionHasErrors(['chips']);

        $this->assertDatabaseMissing('tournaments', [
            'chips' => '',
        ]);
    }

    public function test_backstage_tournaments_create_validate_late_register_required()
    {
        $this->from(route('tournaments.create'))
            ->post(route('tournaments.store'), [
                'avatar' => 1,
                'name'=> 'name',
                'type'=> 1,
                'prize_pool'=> 1,
                'players_limit'=> 1,
                'buy_in'=> 1,
                'commission'=> 1,
                'chips'=> 1,
                'late_register'=> '',
                'late_register_rule'=> 1,
                'state'=> 1,
                'prizes'=> 1,
            ])
            ->assertRedirect(route('tournaments.create'))
            ->assertSessionHasErrors(['late_register']);

        $this->assertDatabaseMissing('tournaments', [
            'late_register' => '',
        ]);
    }

    public function test_backstage_tournaments_create_validate_state_required()
    {
        $this->from(route('tournaments.create'))
            ->post(route('tournaments.store'), [
                'avatar' => 1,
                'name'=> 'name',
                'type'=> 1,
                'prize_pool'=> 1,
                'players_limit'=> 1,
                'buy_in'=> 1,
                'commission'=> 1,
                'chips'=> 1,
                'late_register'=> 1,
                'late_register_rule'=> 1,
                'state'=> '',
                'prizes'=> 1,
            ])
            ->assertRedirect(route('tournaments.create'))
            ->assertSessionHasErrors(['state']);

        $this->assertDatabaseMissing('tournaments', [
            'state' => '',
        ]);
    }

    public function test_backstage_tournaments_create_validate_prizes_required()
    {
        $this->from(route('tournaments.create'))
            ->post(route('tournaments.store'), [
                'avatar' => 1,
                'name'=> 'name',
                'type'=> 1,
                'prize_pool'=> 1,
                'players_limit'=> 1,
                'buy_in'=> 1,
                'commission'=> 1,
                'chips'=> 1,
                'late_register'=> 1,
                'late_register_rule'=> 1,
                'state'=> 1,
                'prizes'=> '',
            ])
            ->assertRedirect(route('tournaments.create'))
            ->assertSessionHasErrors(['prizes']);

        $this->assertDatabaseMissing('tournaments', [
            'prizes' => '',
        ]);
    }

    public function test_backstage_tournaments_store()
    {
        $this->from(route('tournaments.create'))
            ->post(route('tournaments.store'), [
                'name'=> 'name',
                'type'=> 'Single',
                'players_limit'=> 'Heads-Up',
                'buy_in'=> 1,
                'commission'=> 1,
                'chips'=> 1,
                'late_register'=> 1,
                'late_register_rule'=> 1,
                'prize_pool'=> 1,
                'prizes'=> 1,
                'state'=> 'Announced',
            ]);

        $this->assertDatabaseHas('tournaments', [
            'name' => 'name',
        ]);
    }

    public function test_backstage_tournaments_show()
    {
        $tournament = $this->create_Tournament([
            'name' => 'name',
        ]);

        $this->get(route('tournaments.show', $tournament))
            ->assertStatus(200)
            ->assertSee('Show tournament')
            ->assertSee('name');
    }

    public function test_backstage_tournaments_edit()
    {
        $tournament = $this->create_Tournament([
            'name' => 'name',
        ]);

        $this->get(route('tournaments.edit', $tournament))
            ->assertStatus(200)
            ->assertSee('name');
    }

    public function test_backstage_tournaments_update()
    {
        $tournament = $this->create_Tournament();

        $this->put(route('tournaments.update', $tournament), [
                'name'=> 'name',
                'type'=> 'Single',
                'players_limit'=> 'Heads-Up',
                'buy_in'=> 1,
                'commission'=> 1,
                'chips'=> 1,
                'late_register'=> 1,
                'late_register_rule'=> 1,
                'prize_pool'=> 1,
                'prizes'=> 1,
                'state'=> 'Announced',
            ])->assertRedirect(route('tournaments.index'));

        $this->assertDatabaseHas('tournaments', [
            'name' => 'name',
        ]);
    }

    public function test_backstage_tournaments_update_validate_name_required()
    {
        $tournament = $this->create_Tournament();

        $this->from(route('tournaments.edit', $tournament))
            ->put(route('tournaments.update', $tournament), [
                'name'=> '',
                'type'=> 'Single',
                'players_limit'=> 'Heads-Up',
                'buy_in'=> 1,
                'commission'=> 1,
                'chips'=> 1,
                'late_register'=> 1,
                'late_register_rule'=> 1,
                'prize_pool'=> 1,
                'prizes'=> 1,
                'state'=> 'Announced',
            ])
            ->assertRedirect(route('tournaments.edit', $tournament))
            ->assertSessionHasErrors(['name']);

        $this->assertDatabaseMissing('tournaments', [
            'name' => '',
        ]);
    }

    public function test_backstage_tournaments_update_validate_type_required()
    {
        $tournament = $this->create_Tournament();

        $this->from(route('tournaments.edit', $tournament))
            ->put(route('tournaments.update', $tournament), [
                'name'=> 'name',
                'type'=> '',
                'players_limit'=> 'Heads-Up',
                'buy_in'=> 1,
                'commission'=> 1,
                'chips'=> 1,
                'late_register'=> 1,
                'late_register_rule'=> 1,
                'prize_pool'=> 1,
                'prizes'=> 1,
                'state'=> 'Announced',
            ])
            ->assertRedirect(route('tournaments.edit', $tournament))
            ->assertSessionHasErrors(['type']);

        $this->assertDatabaseMissing('tournaments', [
            'type' => '',
        ]);
    }

    public function test_backstage_tournaments_update_validate_players_limit_required()
    {
        $tournament = $this->create_Tournament();

        $this->from(route('tournaments.edit', $tournament))
            ->put(route('tournaments.update', $tournament), [
                'name'=> 'name',
                'type'=> 'Single',
                'players_limit'=> '',
                'buy_in'=> 1,
                'commission'=> 1,
                'chips'=> 1,
                'late_register'=> 1,
                'late_register_rule'=> 1,
                'prize_pool'=> 1,
                'prizes'=> 1,
                'state'=> 'Announced',
            ])
            ->assertRedirect(route('tournaments.edit', $tournament))
            ->assertSessionHasErrors(['players_limit']);

        $this->assertDatabaseMissing('tournaments', [
            'players_limit' => '',
        ]);
    }

    public function test_backstage_tournaments_update_validate_buy_in_required()
    {
        $tournament = $this->create_Tournament();

        $this->from(route('tournaments.edit', $tournament))
            ->put(route('tournaments.update', $tournament), [
                'name'=> 'name',
                'type'=> 'Single',
                'players_limit'=> 'Heads-Up',
                'buy_in'=> '',
                'commission'=> 1,
                'chips'=> 1,
                'late_register'=> 1,
                'late_register_rule'=> 1,
                'prize_pool'=> 1,
                'prizes'=> 1,
                'state'=> 'Announced',
            ])
            ->assertRedirect(route('tournaments.edit', $tournament))
            ->assertSessionHasErrors(['buy_in']);

        $this->assertDatabaseMissing('tournaments', [
            'buy_in' => '',
        ]);
    }

    public function test_backstage_tournaments_update_validate_chips_required()
    {
        $tournament = $this->create_Tournament();

        $this->from(route('tournaments.edit', $tournament))
            ->put(route('tournaments.update', $tournament), [
                'name'=> 'name',
                'type'=> 'Single',
                'players_limit'=> 'Heads-Up',
                'buy_in'=> 1,
                'commission'=> 1,
                'chips'=> '',
                'late_register'=> 1,
                'late_register_rule'=> 1,
                'prize_pool'=> 1,
                'prizes'=> 1,
                'state'=> 'Announced',
            ])
            ->assertRedirect(route('tournaments.edit', $tournament))
            ->assertSessionHasErrors(['chips']);

        $this->assertDatabaseMissing('tournaments', [
            'chips' => '',
        ]);
    }

    public function test_backstage_tournaments_update_validate_commission_required()
    {
        $tournament = $this->create_Tournament();

        $this->from(route('tournaments.edit', $tournament))
            ->put(route('tournaments.update', $tournament), [
                'name'=> 'name',
                'type'=> 'Single',
                'players_limit'=> 'Heads-Up',
                'buy_in'=> 1,
                'commission'=> '',
                'chips'=> 1,
                'late_register'=> 1,
                'late_register_rule'=> 1,
                'prize_pool'=> 1,
                'prizes'=> 1,
                'state'=> 'Announced',
            ])
            ->assertRedirect(route('tournaments.edit', $tournament))
            ->assertSessionHasErrors(['commission']);

        $this->assertDatabaseMissing('tournaments', [
            'commission' => '',
        ]);
    }

    public function test_backstage_tournaments_update_validate_state_required()
    {
        $tournament = $this->create_Tournament();

        $this->from(route('tournaments.edit', $tournament))
            ->put(route('tournaments.update', $tournament), [
                'name'=> 'name',
                'type'=> 'Single',
                'players_limit'=> 'Heads-Up',
                'buy_in'=> 1,
                'commission'=> 1,
                'chips'=> 1,
                'late_register'=> 1,
                'late_register_rule'=> 1,
                'prize_pool'=> 1,
                'prizes'=> 1,
                'state'=> '',
            ])
            ->assertRedirect(route('tournaments.edit', $tournament))
            ->assertSessionHasErrors(['state']);

        $this->assertDatabaseMissing('tournaments', [
            'state' => '',
        ]);
    }

    public function test_backstage_tournaments_update_validate_prizes_required()
    {
        $tournament = $this->create_Tournament();

        $this->from(route('tournaments.edit', $tournament))
            ->put(route('tournaments.update', $tournament), [
                'name'=> 'name',
                'type'=> 'Single',
                'players_limit'=> 'Heads-Up',
                'buy_in'=> 1,
                'commission'=> 1,
                'chips'=> 1,
                'late_register'=> 1,
                'late_register_rule'=> 1,
                'prize_pool'=> 1,
                'prizes'=> '',
                'state'=> 'Announced',
            ])
            ->assertRedirect(route('tournaments.edit', $tournament))
            ->assertSessionHasErrors(['prizes']);

        $this->assertDatabaseMissing('tournaments', [
            'prizes' => '',
        ]);
    }
}
