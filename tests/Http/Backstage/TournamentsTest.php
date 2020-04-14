<?php

namespace Tests\Http\Backstage;

use App\Models\Admin;
use App\Models\Config;
use App\Models\Tournament;
use Tests\Utils\TestCase;

class TournamentsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsAdmin(factory(Admin::class)->create());
    }

    private function createTournament(array $attributes = [])
    {
        return factory(Tournament::class)->create($attributes);
    }

    public function test_backstage_tournaments_index()
    {
        $this->createTournament([
            'name' => 'name',
        ]);

        $this->get(route('tournaments.index'))
            ->assertStatus(200)
            ->assertSee('name');
    }

    public function test_backstage_tournaments_create_form()
    {
        factory(Config::class)
            ->times(1)
            ->create();

        $this->get(route('tournaments.create'))
            ->assertStatus(200)
            ->assertSee('Save');
    }

    public function test_backstage_tournaments_create_validate_name_required()
    {
        $this->from(route('tournaments.create'))
            ->post(route('tournaments.store'), [
                'avatar' => 1,
                'name' => '',
                'prize_pool' => [
                    'type' => 'Fixed',
                    'fixed_value' => 1,
                ],
                'players_limit' => 'Heads-Up',
                'buy_in' => 1,
                'chips' => 1,
                'commission' => 1,
                'late_register' => 1,
                'late_register_rule' => [
                    'interval' => 'seconds',
                    'value' => 1,
                ],
                'state' => "Announced",
                'time_frame' => 'Daily',
            ])
            ->assertRedirect(route('tournaments.create'))
            ->assertSessionHasErrors(['name']);

        $this->assertDatabaseMissing('tournaments', [
            'name' => '',
        ]);
    }

    public function test_backstage_tournaments_create_validate_players_limit_required()
    {
        $this->from(route('tournaments.create'))
            ->post(route('tournaments.store'), [
                'avatar' => 1,
                'name' => 'name',
                'prize_pool' => [
                    'type' => 'Fixed',
                    'fixed_value' => 1,
                ],
                'players_limit' => '',
                'buy_in' => 1,
                'chips' => 1,
                'commission' => 1,
                'late_register' => 1,
                'late_register_rule' => [
                    'interval' => 'seconds',
                    'value' => 1,
                ],
                'state' => "Announced",
                'time_frame' => 'Daily',
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
                'name' => 'name',
                'prize_pool' => [
                    'type' => 'Fixed',
                    'fixed_value' => 1,
                ],
                'players_limit' => 'Heads-Up',
                'buy_in' => '',
                'chips' => 1,
                'commission' => 1,
                'late_register' => 1,
                'late_register_rule' => [
                    'interval' => 'seconds',
                    'value' => 1,
                ],
                'state' => "Announced",
                'time_frame' => 'Daily',
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
                'name' => 'name',
                'prize_pool' => [
                    'type' => 'Fixed',
                    'fixed_value' => 1,
                ],
                'players_limit' => 'Heads-Up',
                'buy_in' => 1,
                'commission' => '',
                'chips' => 1,
                'late_register' => 1,
                'late_register_rule' => [
                    'interval' => 'seconds',
                    'value' => 1,
                ],
                'state' => "Announced",
                'time_frame' => 'Daily',
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
                'name' => 'name',
                'prize_pool' => [
                    'type' => 'Fixed',
                    'fixed_value' => 1,
                ],
                'players_limit' => 'Heads-Up',
                'buy_in' => 1,
                'commission' => 1,
                'chips' => '',
                'late_register' => 1,
                'late_register_rule' => [
                    'interval' => 'seconds',
                    'value' => 1,
                ],
                'state' => "Announced",
                'time_frame' => 'Daily',
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
                'name' => 'name',
                'prize_pool' => [
                    'type' => 'Fixed',
                    'fixed_value' => 1,
                ],
                'players_limit' => 'Unlimited',
                'buy_in' => 1,
                'commission' => 1,
                'chips' => 1,
                'late_register' => '',
                'late_register_rule' => [
                    'interval' => 'seconds',
                    'value' => 1,
                ],
                'state' => "Announced",
                'time_frame' => 'Daily',
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
                'name' => 'name',
                'prize_pool' => [
                    'type' => 'Fixed',
                    'fixed_value' => 1,
                ],
                'players_limit' => 'Heads-Up',
                'buy_in' => 1,
                'commission' => 1,
                'chips' => 1,
                'late_register' => 1,
                'late_register_rule' => [
                    'interval' => 'seconds',
                    'value' => 1,
                ],
                'state' => '',
                'time_frame' => 'Daily',
            ])
            ->assertRedirect(route('tournaments.create'))
            ->assertSessionHasErrors(['state']);

        $this->assertDatabaseMissing('tournaments', [
            'state' => '',
        ]);
    }

    public function test_backstage_tournaments_create_validate_prize_pool_required()
    {
        $this->from(route('tournaments.create'))
            ->post(route('tournaments.store'), [
                'avatar' => 1,
                'name' => 'name',
                'prize_pool' => [
                    'type' => '',
                ],
                'players_limit' => 'Heads-Up',
                'buy_in' => 1,
                'commission' => 1,
                'chips' => 1,
                'late_register' => 1,
                'late_register_rule' => [
                    'interval' => 'seconds',
                    'value' => 1,
                ],
                'state' => "Announced",
                'time_frame' => 'Daily',
            ])
            ->assertRedirect(route('tournaments.create'))
            ->assertSessionHasErrors(['prize_pool.type']);

        $this->assertDatabaseMissing('tournaments', [
            'prize_pool' => '{"type": ""}',
        ]);
    }

    public function test_backstage_tournaments_store()
    {
        $this->from(route('tournaments.create'))
            ->post(route('tournaments.store'), [
                'name' => 'name',
                'players_limit' => 'Heads-Up',
                'buy_in' => 1,
                'commission' => 1,
                'chips' => 1,
                'late_register' => 1,
                'late_register_rule' => [
                    'interval' => 'seconds',
                    'value' => 1,
                ],
                'prize_pool' => [
                    'type' => 'Fixed',
                    'fixed_value' => 1,
                ],
                'state' => 'Announced',
                'time_frame' => 'Daily',
            ])
            ->assertOk();

        $this->assertDatabaseHas('tournaments', [
            'name' => 'name',
        ]);
    }

    public function test_backstage_tournaments_show()
    {
        $tournament = $this->createTournament([
            'name' => 'name',
        ]);

        $this->get(route('tournaments.show', $tournament))
            ->assertStatus(200)
            ->assertSee('Show tournament')
            ->assertSee('name');
    }

    public function test_backstage_tournaments_edit()
    {
        $tournament = $this->createTournament([
            'name' => 'name',
        ]);

        $this->get(route('tournaments.edit', $tournament))
            ->assertStatus(200)
            ->assertSee('name');
    }

    public function test_backstage_tournaments_update()
    {
        $tournament = $this->createTournament();

        $this->put(route('tournaments.update', $tournament), [
            'name' => 'name',
            'players_limit' => 'Heads-Up',
            'buy_in' => 1,
            'commission' => 1,
            'chips' => 1,
            'late_register' => 1,
            'late_register_rule' => [
                'interval' => 'seconds',
                'value' => 1,
            ],
            'prize_pool' => [
                'type' => 'Fixed',
                'fixed_value' => 1,
            ],
            'state' => 'Announced',
            'time_frame' => 'Daily',
        ])->assertSee("Data Updated Successfully");

        $this->assertDatabaseHas('tournaments', [
            'name' => 'name',
        ]);
    }

    public function test_backstage_tournaments_update_validate_name_required()
    {
        $tournament = $this->createTournament();

        $this->from(route('tournaments.edit', $tournament))
            ->put(route('tournaments.update', $tournament), [
                'name' => '',
                'players_limit' => 'Heads-Up',
                'buy_in' => 1,
                'commission' => 1,
                'chips' => 1,
                'late_register' => 1,
                'late_register_rule' => [
                    'interval' => 'seconds',
                    'value' => 1,
                ],
                'prize_pool' => [
                    'type' => 'Fixed',
                    'fixed_value' => 1,
                ],
                'state' => 'Announced',
                'time_frame' => 'Daily',
            ])
            ->assertRedirect(route('tournaments.edit', $tournament))
            ->assertSessionHasErrors(['name']);

        $this->assertDatabaseMissing('tournaments', [
            'name' => '',
        ]);
    }

    public function test_backstage_tournaments_update_validate_players_limit_required()
    {
        $tournament = $this->createTournament();

        $this->from(route('tournaments.edit', $tournament))
            ->put(route('tournaments.update', $tournament), [
                'name' => 'name',
                'players_limit' => '',
                'buy_in' => 1,
                'commission' => 1,
                'chips' => 1,
                'late_register' => 1,
                'late_register_rule' => [
                    'interval' => 'seconds',
                    'value' => 1,
                ],
                'prize_pool' => [
                    'type' => 'Fixed',
                    'fixed_value' => 1,
                ],
                'state' => 'Announced',
                'time_frame' => 'Daily',
            ])
            ->assertRedirect(route('tournaments.edit', $tournament))
            ->assertSessionHasErrors(['players_limit']);

        $this->assertDatabaseMissing('tournaments', [
            'players_limit' => '',
        ]);
    }

    public function test_backstage_tournaments_update_validate_buy_in_required()
    {
        $tournament = $this->createTournament();

        $this->from(route('tournaments.edit', $tournament))
            ->put(route('tournaments.update', $tournament), [
                'name' => 'name',
                'players_limit' => 'Heads-Up',
                'buy_in' => '',
                'commission' => 1,
                'chips' => 1,
                'late_register' => 1,
                'late_register_rule' => [
                    'interval' => 'seconds',
                    'value' => 1,
                ],
                'prize_pool' => [
                    'type' => 'Fixed',
                    'fixed_value' => 1,
                ],
                'state' => 'Announced',
                'time_frame' => 'Daily',
            ])
            ->assertRedirect(route('tournaments.edit', $tournament))
            ->assertSessionHasErrors(['buy_in']);

        $this->assertDatabaseMissing('tournaments', [
            'buy_in' => '',
        ]);
    }

    public function test_backstage_tournaments_update_validate_chips_required()
    {
        $tournament = $this->createTournament();

        $this->from(route('tournaments.edit', $tournament))
            ->put(route('tournaments.update', $tournament), [
                'name' => 'name',
                'players_limit' => 'Heads-Up',
                'buy_in' => 1,
                'commission' => 1,
                'chips' => '',
                'late_register' => 1,
                'late_register_rule' => [
                    'interval' => 'seconds',
                    'value' => 1,
                ],
                'prize_pool' => [
                    'type' => 'Fixed',
                    'fixed_value' => 1,
                ],
                'state' => 'Announced',
                'time_frame' => 'Daily',
            ])
            ->assertRedirect(route('tournaments.edit', $tournament))
            ->assertSessionHasErrors(['chips']);

        $this->assertDatabaseMissing('tournaments', [
            'chips' => '',
        ]);
    }

    public function test_backstage_tournaments_update_validate_commission_required()
    {
        $tournament = $this->createTournament();

        $this->from(route('tournaments.edit', $tournament))
            ->put(route('tournaments.update', $tournament), [
                'name' => 'name',
                'players_limit' => 'Heads-Up',
                'buy_in' => 1,
                'commission' => '',
                'chips' => 1,
                'late_register' => 1,
                'late_register_rule' => [
                    'interval' => 'seconds',
                    'value' => 1,
                ],
                'prize_pool' => [
                    'type' => 'Fixed',
                    'fixed_value' => 1,
                ],
                'state' => 'Announced',
                'time_frame' => 'Daily',
            ])
            ->assertRedirect(route('tournaments.edit', $tournament))
            ->assertSessionHasErrors(['commission']);

        $this->assertDatabaseMissing('tournaments', [
            'commission' => '',
        ]);
    }

    public function test_backstage_tournaments_update_validate_state_required()
    {
        $tournament = $this->createTournament();

        $this->from(route('tournaments.edit', $tournament))
            ->put(route('tournaments.update', $tournament), [
                'name' => 'name',
                'players_limit' => 'Heads-Up',
                'buy_in' => 1,
                'commission' => 1,
                'chips' => 1,
                'late_register' => 1,
                'late_register_rule' => [
                    'interval' => 'seconds',
                    'value' => 1,
                ],
                'prize_pool' => [
                    'type' => 'Fixed',
                    'fixed_value' => 1,
                ],
                'state' => '',
                'time_frame' => 'Daily',
            ])
            ->assertRedirect(route('tournaments.edit', $tournament))
            ->assertSessionHasErrors(['state']);

        $this->assertDatabaseMissing('tournaments', [
            'state' => '',
        ]);
    }

    public function test_backstage_tournaments_update_validate_prize_pool_required()
    {
        $tournament = $this->createTournament();

        $this->from(route('tournaments.edit', $tournament))
            ->put(route('tournaments.update', $tournament), [
                'name' => 'name',
                'players_limit' => 'Heads-Up',
                'buy_in' => 1,
                'commission' => 1,
                'chips' => 1,
                'late_register' => 1,
                'late_register_rule' => [
                    'interval' => 'seconds',
                    'value' => 1,
                ],
                'prize_pool' => [
                    'type' => '',
                ],
                'state' => 'Announced',
                'time_frame' => 'Daily',
            ])
            ->assertRedirect(route('tournaments.edit', $tournament))
            ->assertSessionHasErrors(['prize_pool.type']);

        $this->assertDatabaseMissing('tournaments', [
            'prize_pool' => '{"type": ""}',
        ]);
    }
}
