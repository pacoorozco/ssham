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

use App\Enums\Permissions;
use App\Models\Host;
use App\Models\Hostgroup;
use App\Models\User;
use Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\InteractsWithPermissions;

class HostControllerTest extends TestCase
{
    use RefreshDatabase;
    use InteractsWithPermissions;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->setupRolesAndPermissions();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function users_should_not_see_the_index_view(): void
    {
        $this
            ->actingAs($this->user)
            ->get(route('hosts.index'))
            ->assertForbidden();
    }

    /** @test */
    public function viewers_should_see_the_index_view(): void
    {
        $this->user->givePermissionTo(Permissions::ViewHosts);

        $this
            ->actingAs($this->user)
            ->get(route('hosts.index'))
            ->assertSuccessful()
            ->assertViewIs('host.index');
    }

    /** @test */
    public function users_should_not_see_any_host(): void
    {
        $host = Host::factory()->create();

        $this
            ->actingAs($this->user)
            ->get(route('hosts.show', $host))
            ->assertForbidden();
    }

    /** @test */
    public function viewers_should_see_any_host(): void
    {
        $this->user->givePermissionTo(Permissions::ViewHosts);

        $host = Host::factory()->create();

        $this
            ->actingAs($this->user)
            ->get(route('hosts.show', $host))
            ->assertSuccessful()
            ->assertViewIs('host.show')
            ->assertViewHas('host', $host);
    }

    /** @test */
    public function users_should_not_see_the_new_host_form(): void
    {
        Host::factory()->create();

        $this
            ->actingAs($this->user)
            ->get(route('hosts.create'))
            ->assertForbidden();
    }

    /** @test */
    public function editors_should_see_the_new_host_form(): void
    {
        $this->user->givePermissionTo(Permissions::EditHosts);

        $keys = Hostgroup::factory()
            ->count(2)
            ->create();

        $this
            ->actingAs($this->user)
            ->get(route('hosts.create'))
            ->assertSuccessful()
            ->assertViewIs('host.create')
            ->assertViewHas('groups', $keys->pluck('name', 'id'));
    }

    /** @test */
    public function users_should_not_create_hosts(): void
    {
        /** @var Host $want */
        $want = Host::factory()->make();

        $this
            ->actingAs($this->user)
            ->post(route('hosts.store'), [
                'hostname' => $want->hostname,
                'username' => $want->username,
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing(Host::class, [
            'hostname' => $want->hostname,
            'username' => $want->username,
        ]);
    }

    /**
     * @test
     * @dataProvider provideNullableFieldsForHosts
     */
    public function editors_should_create_hosts(
        array $nullable,
    ): void {
        $this->user->givePermissionTo(Permissions::EditHosts);

        // Create some hosts groups to test the membership.
        $groups = Hostgroup::factory()
            ->count(2)
            ->create();

        /** @var Host $want */
        $want = Host::factory()
            ->customized()
            ->make();

        $this
            ->actingAs($this->user)
            ->post(route('hosts.store'), [
                'hostname' => $want->hostname,
                'username' => $want->username,
                'enabled' => $want->enabled,
                'port' => $nullable['port'] ?? $want->port,
                'authorized_keys_file' => $nullable['authorized_keys_file'] ?? $want->authorized_keys_file,
                'groups' => $groups->pluck('id')->toArray(),
            ])
            ->assertRedirect(route('hosts.index'))
            ->assertValid();

        $host = Host::query()
            ->where('hostname', $want->hostname)
            ->where('username', $want->username)
            ->where('enabled', $want->enabled)
            ->where('port', $nullable['port'] ?? $want->port)
            ->where('authorized_keys_file', $nullable['authorized_keys_file'] ?? $want->authorized_keys_file)
            ->first();

        $this->assertInstanceOf(Host::class, $host);

        $this->assertCount(count($groups), $host->groups);
    }

    public function provideNullableFieldsForHosts(): Generator
    {
        yield 'without null values' => [
            'nullable' => [],
        ];

        yield 'null port' => [
            'nullable' => [
                'port' => null,
            ],
        ];

        yield 'null authorized_keys_file' => [
            'nullable' => [
                'authorized_keys_file' => null,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provideWrongDataForHostCreation
     */
    public function editors_should_get_errors_when_creating_hosts_with_wrong_data(
        array $data,
        array $errors
    ): void {
        $this->user->givePermissionTo(Permissions::EditHosts);

        // Host to validate unique rules...
        Host::factory()->create([
            'hostname' => 'foo',
            'username' => 'bar',
            'authorized_keys_file' => 'already-created-host',
        ]);

        /** @var Host $want */
        $want = Host::factory()->make();

        $formData = [
            'hostname' => $data['hostname'] ?? $want->hostname,
            'username' => $data['username'] ?? $want->username,
            'enabled' => $data['enabled'] ?? $want->enabled,
            'port' => $data['port'] ?? $want->port,
            'authorized_keys_file' => $data['authorized_keys_file'] ?? $want->authorized_keys_file,
            'groups' => $data['groups'] ?? [],
        ];

        $this
            ->actingAs($this->user)
            ->post(route('hosts.store'), $formData)
            ->assertInvalid($errors);

        $this->assertDatabaseMissing(Host::class, [
            'hostname' => $formData['hostname'],
            'username' => $formData['username'],
            'enabled' => $formData['enabled'],
            'port' => $formData['port'],
            'authorized_keys_file' => $formData['authorized_keys_file'],
        ]);
    }

    public function provideWrongDataForHostCreation(): Generator
    {
        yield 'hostname is empty' => [
            'data' => [
                'hostname' => '',
            ],
            'errors' => ['hostname'],
        ];

        yield 'hostname > 255 chars' => [
            'data' => [
                'hostname' => '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345',
            ],
            'errors' => ['hostname'],
        ];

        yield 'username is empty' => [
            'data' => [
                'username' => '',
            ],
            'errors' => ['username'],
        ];

        yield 'username > 255 chars' => [
            'data' => [
                'username' => '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345',
            ],
            'errors' => ['username'],
        ];

        yield 'hostname & username is taken' => [
            'data' => [
                'hostname' => 'foo',
                'username' => 'bar',
            ],
            'errors' => ['hostname'],
        ];

        yield 'port ! valid' => [
            'data' => [
                'port' => 'non-integer',
            ],
            'errors' => ['port'],
        ];

        yield 'enabled ! valid' => [
            'data' => [
                'enabled' => 'non-boolean',
            ],
            'errors' => ['enabled'],
        ];

        yield 'authorized_keys_file is empty' => [
            'data' => [
                'authorized_keys_file' => '',
            ],
            'errors' => ['authorized_keys_file'],
        ];

        yield 'authorized_keys_file > 255 chars' => [
            'data' => [
                'authorized_keys_file' => '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345',
            ],
            'errors' => ['authorized_keys_file'],
        ];
    }

    /** @test */
    public function users_should_not_see_edit_host_form(): void
    {
        $host = Host::factory()->create();

        // Create some groups to test the membership.
        Hostgroup::factory()->create();

        $this
            ->actingAs($this->user)
            ->get(route('hosts.edit', $host))
            ->assertForbidden();
    }

    /** @test */
    public function editors_should_see_the_edit_host_form(): void
    {
        $this->user->givePermissionTo(Permissions::EditHosts);

        $host = Host::factory()->create();

        // Create some keys to test the membership.
        $groups = Hostgroup::factory()
            ->count(2)
            ->create();

        $this
            ->actingAs($this->user)
            ->get(route('hosts.edit', $host))
            ->assertSuccessful()
            ->assertViewIs('host.edit')
            ->assertViewHas('host', $host)
            ->assertViewHas('groups', $groups->pluck('name', 'id'));
    }

    /**
     * @test
     * @dataProvider provideNullableFieldsForHosts
     */
    public function editors_should_update_hosts(
        array $nullable,
    ): void {
        $this->user->givePermissionTo(Permissions::EditHosts);

        // Create some groups to test the membership.
        $groups = Hostgroup::factory()
            ->count(2)
            ->create();

        /** @var Host $host */
        $host = Host::factory()->create();

        /** @var Host $want */
        $want = Host::factory()
            ->customized()
            ->make();

        $this
            ->actingAs($this->user)
            ->put(route('hosts.update', $host), [
                'enabled' => $want->enabled,
                'port' => $nullable['port'] ?? $want->port,
                'authorized_keys_file' => $nullable['authorized_keys_file'] ?? $want->authorized_keys_file,
                'groups' => $groups->pluck('id')->toArray(),
            ])
            ->assertRedirect(route('hosts.index'))
            ->assertValid();

        $this->assertDatabaseHas(Host::class, [
            'hostname' => $host->hostname,
            'username' => $host->username,
            'enabled' => $want->enabled,
            'port' => $nullable['port'] ?? $want->port,
            'authorized_keys_file' => $nullable['authorized_keys_file'] ?? $want->authorized_keys_file,
        ]);

        $host->refresh();

        $this->assertCount(count($groups), $host->groups);
    }

    /**
     * @test
     * @dataProvider provideWrongDataForHostModification
     */
    public function editors_should_get_errors_when_updating_hosts_with_wrong_data(
        array $data,
        array $errors
    ): void {
        $this->user->givePermissionTo(Permissions::EditHosts);

        /** @var Host $host */
        $host = Host::factory()->create();

        /** @var Host $want */
        $want = Host::factory()
            ->customized()
            ->make();

        $formData = [
            'enabled' => $data['enabled'] ?? $want->enabled,
            'port' => $data['port'] ?? $want->port,
            'authorized_keys_file' => $data['authorized_keys_file'] ?? $want->authorized_keys_file,
            'groups' => $data['groups'] ?? [],
        ];

        $this
            ->actingAs($this->user)
            ->put(route('hosts.update', $host), $formData)
            ->assertInvalid($errors);

        $this->assertDatabaseMissing(Host::class, [
            'id' => $host->id,
            'hostname' => $host->hostname,
            'username' => $host->username,
            'enabled' => $formData['enabled'],
            'port' => $formData['port'],
            'authorized_keys_file' => $formData['authorized_keys_file'],
        ]);

        $host->refresh();

        $this->assertCount(0, $host->groups);
    }

    public function provideWrongDataForHostModification(): Generator
    {
        yield 'port ! valid' => [
            'data' => [
                'port' => 'non-integer',
            ],
            'errors' => ['port'],
        ];

        yield 'enabled ! valid' => [
            'data' => [
                'enabled' => 'non-boolean',
            ],
            'errors' => ['enabled'],
        ];

        yield 'authorized_keys_file is empty' => [
            'data' => [
                'authorized_keys_file' => '',
            ],
            'errors' => ['authorized_keys_file'],
        ];

        yield 'authorized_keys_file > 255 chars' => [
            'data' => [
                'authorized_keys_file' => '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345',
            ],
            'errors' => ['authorized_keys_file'],
        ];

        yield 'groups ! a group' => [
            'data' => [
                'port' => 2022,
                'groups' => [1],
            ],
            'errors' => ['groups'],
        ];
    }

    /** @test */
    public function users_should_not_delete_hosts(): void
    {
        $host = Host::factory()->create();

        $this
            ->actingAs($this->user)
            ->delete(route('hosts.destroy', $host))
            ->assertForbidden();
    }

    /** @test */
    public function eliminators_should_delete_hosts(): void
    {
        $this->user->givePermissionTo(Permissions::DeleteHosts);

        $host = Host::factory()->create();

        $this
            ->actingAs($this->user)
            ->delete(route('hosts.destroy', $host))
            ->assertRedirect(route('hosts.index'));

        $this->assertModelMissing($host);
    }
}
