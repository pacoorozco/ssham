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
use Generator;
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
    public function it_shows_the_index_view(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('hosts.index'));

        $response->assertSuccessful();

        $response->assertViewIs('host.index');
    }

    /** @test */
    public function it_shows_the_new_host_form(): void
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
    public function it_creates_a_new_host(): void
    {
        /** @var Host $want */
        $want = Host::factory()->make();

        $response = $this
            ->actingAs($this->user)
            ->post(route('hosts.store'), [
                'hostname' => $want->hostname,
                'username' => $want->username,
                'enabled' => $want->enabled,
                'port' => $want->port,
                'authorized_keys_file' => $want->authorized_keys_file,
            ]);

        $response->assertRedirect(route('hosts.index'));

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas(Host::class, [
            'hostname' => $want->hostname,
            'username' => $want->username,
            'enabled' => $want->enabled,
            'port' => $want->port,
            'authorized_keys_file' => $want->authorized_keys_file,
        ]);
    }

    /**
     * @test
     * @dataProvider provideWrongDataForHostCreation
     */
    public function it_returns_errors_when_creating_a_new_host(
        array $data,
        array $errors,
    ): void {
        /** @var Host $want */
        $want = Host::factory()->make();

        $formData = [
            'hostname' => $data['hostname'] ?? $want->hostname,
            'username' => $data['username'] ?? $want->username,
            'enabled' => $data['enabled'] ?? $want->enabled,
            'port' => $data['port'] ?? $want->port,
            'authorized_keys_file' => $data['authorized_keys_file'] ?? $want->authorized_keys_file,
        ];

        $response = $this
            ->actingAs($this->user)
            ->post(route('hosts.store'), $formData);

        $response->assertSessionHasErrors($errors);

        $this->assertDatabaseMissing(Host::class, [
            'hostname' => $formData['hostname'],
            'username' => $formData['username'],
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
    public function it_ensures_uniqueness_when_creating_a_host(): void {
        /** @var Host $want */
        $want = Host::factory()->create([
            'hostname' => 'existing-server.domain.local',
            'username' => 'admin',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->post(route('hosts.store'), [
                'hostname' => $want->hostname,
                'username' => $want->username,
                'enabled' => true,
            ]);

        $response->assertSessionHasErrors(['hostname']);

        $this->assertDatabaseCount(Host::class, 1);
    }

    /** @test */
    public function it_shows_the_edit_host_form(): void
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
    public function it_updates_the_host(): void
    {
        /** @var Host $host */
        $host = Host::factory()->create();

        /** @var Host $want */
        $want = Host::factory()->make();

        $response = $this
            ->actingAs($this->user)
            ->put(route('hosts.update', $host), [
                'enabled' => $want->enabled,
                'port' => $want->port,
                'authorized_keys_file' => $want->authorized_keys_file,
            ]);

        $response->assertRedirect(route('hosts.index'));

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas(Host::class, [
            'id' => $host->id,
            'hostname' => $host->hostname,
            'username' => $host->username,
            'enabled' => $want->enabled,
            'port' => $want->port,
            'authorized_keys_file' => $want->authorized_keys_file,
        ]);
    }

    /**
     * @test
     * @dataProvider provideWrongDataForHostModification
     */
    public function it_returns_errors_when_updating_a_host(
        array $data,
        array $errors,
    ): void
    {
        /** @var Host $host */
        $host = Host::factory()->create();

        /** @var Host $want */
        $want = Host::factory()->make();

        $formData = [
            'enabled' => $data['enabled'] ?? $want->enabled,
            'port' => $data['port'] ?? $want->port,
            'authorized_keys_file' => $data['authorized_keys_file'] ?? $want->authorized_keys_file,
        ];

        $response = $this
            ->actingAs($this->user)
            ->put(route('hosts.update', $host), $formData);

        $response->assertSessionHasErrors($errors);

        $this->assertDatabaseMissing(Host::class, [
            'id' => $host->id,
            'hostname' => $host->hostname,
            'username' => $host->username,
            'enabled' => $formData['enabled'],
            'port' => $formData['port'],
            'authorized_keys_file' => $formData['authorized_keys_file'],
        ]);
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
    }

    /** @test */
    public function it_can_delete_a_host(): void
    {
        $host = Host::factory()
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->delete(route('hosts.destroy', $host));

        $response->assertRedirect(route('hosts.index'));

        $response->assertSessionHas('success');

        $this->assertModelMissing($host);
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
