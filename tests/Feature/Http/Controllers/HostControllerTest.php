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
 *
 * @link        https://github.com/pacoorozco/ssham
 */

namespace Tests\Feature\Http\Controllers;

use App\Models\Host;
use App\Models\Hostgroup;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\InteractsWithPermissions;

class HostControllerTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;
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
            ->get(route('hosts.index'));

        $response->assertSuccessful();
        $response->assertViewIs('host.index');
    }

    /** @test */
    public function create_method_should_return_proper_view(): void
    {
        $groups = Hostgroup::factory()
            ->count(3)
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->get(route('hosts.create'));

        $response->assertSuccessful();
        $response->assertViewIs('host.create');
        $response->assertViewHas('groups', $groups->pluck('name', 'id'));
    }

    /** @test */
    public function store_method_should_create_a_new_host(): void
    {
        $host = Host::factory()->make();

        $response = $this
            ->actingAs($this->user)
            ->post(route('hosts.store'), [
                'hostname' => $host->hostname,
                'username' => $host->username,
                'enabled' => $host->enabled,
                'port' => $host->port,
                'authorized_keys_file' => $host->authorized_keys_file,
            ]);

        $response->assertRedirect(route('hosts.index'));
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('hosts', [
            'hostname' => $host->hostname,
            'username' => $host->username,
            'enabled' => $host->enabled,
            'port' => $host->port,
            'authorized_keys_file' => $host->authorized_keys_file,
        ]);
    }

    /** @test */
    public function edit_method_should_return_proper_view(): void
    {
        $host = Host::factory()
            ->create();
        $groups = Hostgroup::factory()
            ->count(3)
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->get(route('hosts.edit', $host));

        $response->assertSuccessful();
        $response->assertViewIs('host.edit');
        $response->assertViewHas('host', $host);
        $response->assertViewHas('groups', $groups->pluck('name', 'id'));
    }

    /** @test */
    public function update_method_should_update_host(): void
    {
        $want = Host::factory()->make();
        $host = Host::factory()->create();

        $response = $this
            ->actingAs($this->user)
            ->put(route('hosts.update', $host), [
                'enabled' => $want->enabled,
                'port' => $want->port,
                'authorized_keys_file' => $want->authorized_keys_file,
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('hosts', [
            'id' => $host->id,
            'hostname' => $host->hostname,
            'username' => $host->username,
            'enabled' => $want->enabled,
            'port' => $want->port,
            'authorized_keys_file' => $want->authorized_keys_file,
        ]);
    }

    /** @test */
    public function destroy_method_should_return_proper_success_message(): void
    {
        $host = Host::factory()
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->delete(route('hosts.destroy', $host->id));

        $response->assertRedirect(route('hosts.index'));
        $response->assertSessionHas('success');
        $this->assertDeleted($host);
    }

    /** @test */
    public function data_method_should_return_error_when_not_ajax(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('hosts.data'));

        $response->assertForbidden();
    }

    /** @test */
    public function data_method_should_return_data(): void
    {
        $hosts = Host::factory()
            ->count(3)
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->ajaxGet(route('hosts.data'));

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'hostname',
                    'username',
                    'groups',
                    'actions',
                ],
            ],
        ]);
        foreach ($hosts as $host) {
            $response->assertJsonFragment([
                'hostname' => $host['hostname'],
                'username' => $host['username'],
            ]);
        }
    }
}
