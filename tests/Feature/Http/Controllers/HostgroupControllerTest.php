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
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\InteractsWithPermissions;

class HostgroupControllerTest extends TestCase
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
            ->get(route('hostgroups.index'))
            ->assertForbidden();
    }

    /** @test */
    public function viewers_should_see_the_index_view(): void
    {
        $this->user->givePermissionTo(Permissions::ViewHosts);

        $this
            ->actingAs($this->user)
            ->get(route('hostgroups.index'))
            ->assertSuccessful()
            ->assertViewIs('hostgroup.index');
    }

    /** @test */
    public function users_should_not_see_any_hosts_group(): void
    {
        $group = Hostgroup::factory()->create();

        $this
            ->actingAs($this->user)
            ->get(route('hostgroups.show', $group))
            ->assertForbidden();
    }

    /** @test */
    public function viewers_should_see_any_hosts_group(): void
    {
        $this->user->givePermissionTo(Permissions::ViewHosts);

        $group = Hostgroup::factory()->create();

        $this
            ->actingAs($this->user)
            ->get(route('hostgroups.show', $group))
            ->assertSuccessful()
            ->assertViewIs('hostgroup.show')
            ->assertViewHas('hostgroup', $group);
    }

    /** @test */
    public function users_should_not_see_the_new_group_form(): void
    {
        Host::factory()->create();

        $this
            ->actingAs($this->user)
            ->get(route('hostgroups.create'))
            ->assertForbidden();
    }

    /** @test */
    public function editors_should_see_the_new_group_form(): void
    {
        $this->user->givePermissionTo(Permissions::EditHosts);

        $keys = Host::factory()
            ->count(2)
            ->create();

        $this
            ->actingAs($this->user)
            ->get(route('hostgroups.create'))
            ->assertSuccessful()
            ->assertViewIs('hostgroup.create')
            ->assertViewHas('hosts', $keys->pluck('hostname', 'id'));
    }

    /** @test */
    public function users_should_not_create_groups(): void
    {
        /** @var Hostgroup $group */
        $group = Hostgroup::factory()->make();

        $this
            ->actingAs($this->user)
            ->post(route('hostgroups.store'), [
                'name'        => $group->name,
                'description' => $group->description,
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing(Hostgroup::class, [
            'name'        => $group->name,
            'description' => $group->description,
        ]);
    }

    /** @test */
    public function editors_should_create_groups(): void
    {
        $this->user->givePermissionTo(Permissions::EditHosts);

        // Create some keys to test the membership.
        $hosts = Host::factory()
            ->count(2)
            ->create();

        /** @var Hostgroup $want */
        $want = Hostgroup::factory()->make();

        $this
            ->actingAs($this->user)
            ->post(route('hostgroups.store'), [
                'name'        => $want->name,
                'description' => $want->description,
                'hosts'       => $hosts->pluck('id')->toArray(),
            ])
            ->assertRedirect(route('hostgroups.index'))
            ->assertValid();

        $group = Hostgroup::query()
            ->where('name', $want->name)
            ->where('description', $want->description)
            ->first();

        $this->assertInstanceOf(Hostgroup::class, $group);

        $this->assertCount(count($hosts), $group->hosts);
    }

    /**
     * @test
     * @dataProvider provideWrongDataForGroupCreation
     */
    public function editors_should_get_errors_when_creating_groups_with_wrong_data(
        array $data,
        array $errors
    ): void {
        $this->user->givePermissionTo(Permissions::EditHosts);

        // Group to validate unique rules...
        Hostgroup::factory()->create([
            'name' => 'foo',
        ]);

        /** @var Hostgroup $want */
        $want = Hostgroup::factory()->make();

        $formData = [
            'name'        => $data['name'] ?? $want->name,
            'description' => $data['description'] ?? $want->description,
            'hosts'       => $data['hosts'] ?? [],
        ];

        $this
            ->actingAs($this->user)
            ->post(route('hostgroups.store'), $formData)
            ->assertInvalid($errors);

        $this->assertDatabaseMissing(Hostgroup::class, [
            'name'        => $formData['name'],
            'description' => $formData['description'],
        ]);
    }

    public function provideWrongDataForGroupCreation(): Generator
    {
        yield 'name is empty' => [
            'data' => [
                'name' => '',
            ],
            'errors' => ['name'],
        ];

        yield 'name < 5 chars' => [
            'data' => [
                'name' => 'foo',
            ],
            'errors' => ['name'],
        ];

        yield 'name > 255 chars' => [
            'data' => [
                'name' => '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345',
            ],
            'errors' => ['name'],
        ];

        yield 'name is taken' => [
            'data' => [
                'name' => 'foo',
            ],
            'errors' => ['name'],
        ];

        yield 'hosts ! a host' => [
            'data' => [
                'hosts' => [1],
            ],
            'errors' => ['hosts'],
        ];
    }

    /** @test */
    public function users_should_not_see_edit_group_form(): void
    {
        $group = Hostgroup::factory()->create();

        // Create some hosts to test the membership.
        Host::factory()->create();

        $this
            ->actingAs($this->user)
            ->get(route('hostgroups.edit', $group))
            ->assertForbidden();
    }

    /** @test */
    public function editors_should_see_the_edit_group_form(): void
    {
        $this->user->givePermissionTo(Permissions::EditHosts);

        $group = Hostgroup::factory()->create();

        // Create some hosts to test the membership.
        $hosts = Host::factory()
            ->count(2)
            ->create();

        $this
            ->actingAs($this->user)
            ->get(route('hostgroups.edit', $group))
            ->assertSuccessful()
            ->assertViewIs('hostgroup.edit')
            ->assertViewHas('hostgroup', $group)
            ->assertViewHas('hosts', $hosts->pluck('hostname', 'id'));
    }

    /** @test */
    public function editors_should_update_groups(): void
    {
        $this->user->givePermissionTo(Permissions::EditHosts);

        // Create some hosts to test the membership.
        $hosts = Host::factory()
            ->count(2)
            ->create();

        /** @var Hostgroup $group */
        $group = Hostgroup::factory()->create();

        /** @var Hostgroup $want */
        $want = Hostgroup::factory()->make();

        $this
            ->actingAs($this->user)
            ->put(route('hostgroups.update', $group), [
                'name'        => $want->name,
                'description' => $want->description,
                'hosts'       => $hosts->pluck('id')->toArray(),
            ])
            ->assertRedirect(route('hostgroups.edit', $group))
            ->assertValid();

        $group->refresh();

        $this->assertModelExists($group);

        $this->assertCount(count($hosts), $group->hosts);
    }

    /**
     * @test
     * @dataProvider provideWrongDataForGroupModification
     */
    public function editors_should_get_errors_when_updating_groups_with_wrong_data(
        array $data,
        array $errors
    ): void {
        $this->user->givePermissionTo(Permissions::EditHosts);

        // Group to validate unique rules...
        Hostgroup::factory()->create([
            'name' => 'foo',
        ]);

        /** @var Hostgroup $group */
        $group = Hostgroup::factory()->create();

        /** @var Hostgroup $want */
        $want = Hostgroup::factory()->make();

        $formData = [
            'name'        => $data['name'] ?? $want->name,
            'description' => $data['description'] ?? $want->description,
            'hosts'       => $data['hosts'] ?? [],
        ];

        $this
            ->actingAs($this->user)
            ->put(route('hostgroups.update', $group), $formData)
            ->assertInvalid($errors);

        $this->assertDatabaseMissing(Hostgroup::class, [
            'id'          => $group->id,
            'name'        => $formData['name'],
            'description' => $formData['description'],
        ]);

        $group->refresh();

        $this->assertCount(0, $group->hosts);
    }

    public function provideWrongDataForGroupModification(): Generator
    {
        yield 'name is empty' => [
            'data' => [
                'name' => '',
            ],
            'errors' => ['name'],
        ];

        yield 'name < 5 chars' => [
            'data' => [
                'name' => 'foo',
            ],
            'errors' => ['name'],
        ];

        yield 'name > 255 chars' => [
            'data' => [
                'name' => '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345',
            ],
            'errors' => ['name'],
        ];

        yield 'name is taken' => [
            'data' => [
                'name' => 'foo',
            ],
            'errors' => ['name'],
        ];

        yield 'hosts ! a host' => [
            'data' => [
                'hosts' => [1],
            ],
            'errors' => ['hosts'],
        ];
    }

    /** @test */
    public function users_should_not_delete_groups(): void
    {
        $group = Hostgroup::factory()->create();

        $this
            ->actingAs($this->user)
            ->delete(route('hostgroups.destroy', $group))
            ->assertForbidden();
    }

    /** @test */
    public function eliminators_should_delete_groups(): void
    {
        $this->user->givePermissionTo(Permissions::DeleteHosts);

        $group = Hostgroup::factory()->create();

        $this
            ->actingAs($this->user)
            ->delete(route('hostgroups.destroy', $group))
            ->assertRedirect(route('hostgroups.index'));

        $this->assertModelMissing($group);
    }
}
