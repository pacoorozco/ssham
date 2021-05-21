<?php
/**
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2020 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 - 2020 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

namespace Tests\Feature\Http\Controllers;

use App\Models\ControlRule;
use App\Models\Hostgroup;
use App\Models\Keygroup;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ControlRuleControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()
            ->create();
    }

    /** @test */
    public function index_method_returns_proper_view(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('rules.index'));

        $response->assertSuccessful();
        $response->assertViewIs('rule.index');
    }

    /** @test */
    public function create_method_returns_proper_view(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('rules.create'));

        $response->assertSuccessful();
        $response->assertViewIs('rule.create');
    }

    /** @test */
    public function create_method_returns_proper_data(): void
    {
        $sources = Keygroup::factory()
            ->count(3)
            ->create();
        $targets = Hostgroup::factory()
            ->count(3)
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->get(route('rules.create'));

        $response->assertSuccessful();
        $response->assertViewHas('sources', $sources->pluck('name', 'id'));
        $response->assertViewHas('targets', $targets->pluck('name', 'id'));
    }

    /** @test  */
    public function create_method_create_rule_in_database(): void
    {
        $expectedControlRule = ControlRule::factory()->make();
        $formData = [
          'name' =>   $expectedControlRule->name,
          'source' => $expectedControlRule->source,
          'target' => $expectedControlRule->target,
          'action' => $expectedControlRule->action,
        ];

        $response = $this
            ->actingAs($this->user)
            ->post(route('rules.create'), $formData);

        $response->assertRedirect(route('rules.index'));
        $this->assertDatabaseHas('hostgroup_keygroup_permissions', [
            'name' => $expectedControlRule->name,
            'source' => $expectedControlRule->source,
            'target' => $expectedControlRule->target,
            'action' => $expectedControlRule->action,
        ]);

    }

    public function test_destroy_method_returns_proper_success_message()
    {
        $rule = ControlRule::factory()
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->delete(route('rules.destroy', $rule->id));

        $response->assertSessionHas('success');
    }

    public function test_data_method_returns_error_when_not_ajax()
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('rules.data'));

        $response->assertForbidden();
    }

    public function test_data_method_returns_data()
    {
        $rules = ControlRule::factory()
            ->count(3)
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->ajaxGet(route('rules.data'));

        $response->assertSuccessful();
        foreach ($rules as $rule) {
            $response->assertJsonFragment([
                'source' => $rule->source,
                'target' => $rule->target,
                'name' => $rule->name,
            ]);
        }
    }
}
