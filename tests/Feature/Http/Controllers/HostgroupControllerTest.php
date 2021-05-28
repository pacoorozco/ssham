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

use App\Models\Host;
use App\Models\Hostgroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HostgroupControllerTest extends TestCase
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
    public function index_method_should_return_proper_view(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('hostgroups.index'));

        $response->assertSuccessful();
        $response->assertViewIs('hostgroup.index');
    }

    /** @test */
    public function create_method_should_return_proper_view(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('hostgroups.create'));

        $response->assertSuccessful();
        $response->assertViewIs('hostgroup.create');
    }

    /** @test */
    public function create_method_should_return_proper_data(): void
    {
        $hosts = Host::factory()
            ->count(3)
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->get(route('hostgroups.create'));

        $response->assertSuccessful();
        $response->assertViewHas('hosts', $hosts->pluck('hostname', 'id'));
    }

    /** @test */
    public function edit_method_should_return_proper_view(): void
    {
        $group = Hostgroup::factory()
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->get(route('hostgroups.edit', $group->id));

        $response->assertSuccessful();
        $response->assertViewIs('hostgroup.edit');
        $response->assertViewHas('hostgroup', $group);
    }

    /** @test */
    public function edit_method_should_return_proper_data(): void
    {
        $group = Hostgroup::factory()
            ->create();
        $hosts = Host::factory()
            ->count(3)
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->get(route('hostgroups.edit', $group->id));

        $response->assertSuccessful();
        $response->assertViewHas('hosts', $hosts->pluck('hostname', 'id'));
    }

    /** @test */
    public function destroy_method_should_remove_group_and_returns_success(): void
    {
        $group = Hostgroup::factory()
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->delete(route('hostgroups.destroy', $group));

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('hostgroups', [
            'id' => $group->id,
        ]);
    }

    /** @test */
    public function data_method_should_return_error_when_not_ajax(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('hostgroups.data'));

        $response->assertForbidden();
    }

    /** @test */
    public function data_method_should_return_data(): void
    {
        $groups = Hostgroup::factory()
            ->count(3)
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->ajaxGet(route('hostgroups.data'));

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'name',
                    'description',
                    'hosts',
                    'rules',
                    'actions',
                ],
            ],
        ]);
        foreach ($groups as $group) {
            $response->assertJsonFragment([
                'name' => $group['name'],
                'description' => $group['description'],
            ]);
        }
    }

    /** @test */
    public function store_method_should_create_a_new_group(): void
    {
        $group = Hostgroup::factory()->make();

        $response = $this
            ->actingAs($this->user)
            ->post(route('hostgroups.store'), [
                'name' => $group->name,
                'description' => $group->description,
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('hostgroups', [
            'name' => $group->name,
            'description' => $group->description,
        ]);
    }

    /** @test */
    public function update_method_should_update_group(): void
    {
        $want = Hostgroup::factory()->make();
        $group = Hostgroup::factory()->create();

        $response = $this
            ->actingAs($this->user)
            ->put(route('hostgroups.update', $group), [
                'name' => $want->name,
                'description' => $want->description,
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('hostgroups', [
            'id' => $group->id,
            'name' => $want->name,
            'description' => $want->description,
        ]);
    }
}
