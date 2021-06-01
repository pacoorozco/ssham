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
    public function create_method_returns_proper_view_with_data(): void
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
        $response->assertViewIs('rule.create');
        $response->assertViewHas('sources');
        $response->assertViewHas('targets');
    }

    /** @test */
    public function create_method_creates_a_rule(): void
    {
        $expectedControlRule = ControlRule::factory()->make();

        $formData = [
            'name' => $expectedControlRule->name,
            'source' => $expectedControlRule->source->id,
            'target' => $expectedControlRule->target->id,
            'action' => $expectedControlRule->action->value,
        ];

        $response = $this
            ->actingAs($this->user)
            ->post(route('rules.store'), $formData);

        $response->assertRedirect(route('rules.index'));
        $this->assertDatabaseHas('hostgroup_keygroup_permissions', [
            'name' => $expectedControlRule->name,
            'source_id' => $expectedControlRule->source->id,
            'target_id' => $expectedControlRule->target->id,
            'action' => $expectedControlRule->action->value,
        ]);
    }

    /** @test */
    public function destroy_method_removes_the_rule(): void
    {
        $rule = ControlRule::factory()
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->delete(route('rules.destroy', $rule));

        $response->assertRedirect(route('rules.index'));
        $this->assertDatabaseMissing('hostgroup_keygroup_permissions', [
            'id' => $rule->id,
        ]);
    }

    /** @test */
    public function data_method_returns_error_when_not_ajax(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('rules.data'));

        $response->assertForbidden();
    }

    /** @test */
    public function data_method_returns_json_data(): void
    {
        $rules = ControlRule::factory()
            ->count(3)
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->ajaxGet(route('rules.data'));

        //var_dump($response->dump());

        $response->assertSuccessful();
        $response->assertJsonCount(3, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'action',
                    'source',
                    'target',
                    'actions',
                ],
            ],
        ]);
    }
}
