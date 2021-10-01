<?php
/**
 * SSH Access Manager - SSH keygroups management solution.
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
 *
 * @link        https://github.com/pacoorozco/ssham
 */

namespace Tests\Feature\Http\Controllers;

use App\Models\Key;
use App\Models\Keygroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\InteractsWithPermissions;

class KeygroupControllerTest extends TestCase
{
    use RefreshDatabase;
    use InteractsWithPermissions;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->disablePermissionsCheck();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function index_method_should_return_proper_view(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('keygroups.index'));

        $response->assertSuccessful();
        $response->assertViewIs('keygroup.index');
    }

    /** @test */
    public function create_method_should_return_proper_view(): void
    {
        $keys = Key::factory()
            ->count(3)
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->get(route('keygroups.create'));

        $response->assertSuccessful();
        $response->assertViewIs('keygroup.create');
        $response->assertViewHas('keys', $keys->pluck('username', 'id'));
    }

    /** @test */
    public function store_method_should_create_a_new_group(): void
    {
        $group = Keygroup::factory()->make();

        $response = $this
            ->actingAs($this->user)
            ->post(route('keygroups.store'), [
                'name' => $group->name,
                'description' => $group->description,
            ]);

        $response->assertRedirect(route('keygroups.index'));
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('keygroups', [
            'name' => $group->name,
            'description' => $group->description,
        ]);
    }

    /** @test */
    public function edit_method_should_return_proper_view(): void
    {
        $group = Keygroup::factory()
            ->create();
        $keys = Key::factory()
            ->count(3)
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->get(route('keygroups.edit', $group->id));

        $response->assertSuccessful();
        $response->assertViewIs('keygroup.edit');
        $response->assertViewHas('keygroup', $group);
        $response->assertViewHas('keys', $keys->pluck('username', 'id'));
    }

    /** @test */
    public function update_method_should_update_group(): void
    {
        $want = Keygroup::factory()->make();
        $group = Keygroup::factory()->create();

        $response = $this
            ->actingAs($this->user)
            ->put(route('keygroups.update', $group), [
                'name' => $want->name,
                'description' => $want->description,
            ]);

        $response->assertRedirect(route('keygroups.edit', $group));
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('keygroups', [
            'id' => $group->id,
            'name' => $want->name,
            'description' => $want->description,
        ]);
    }

    /** @test */
    public function destroy_method_should_remove_group_and_returns_success(): void
    {
        $group = Keygroup::factory()
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->delete(route('keygroups.destroy', $group));

        $response->assertSessionHas('success');
        $this->assertDeleted($group);
    }

    /** @test */
    public function data_method_should_return_error_when_not_ajax(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('keygroups.data'));

        $response->assertForbidden();
    }

    /** @test */
    public function data_method_should_return_data(): void
    {
        $groups = Keygroup::factory()
            ->count(3)
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->ajaxGet(route('keygroups.data'));

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'name',
                    'description',
                    'keys',
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
}
