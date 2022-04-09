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

        $this->disablePermissionsCheck();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_should_show_the_index(): void
    {
        $this
            ->actingAs($this->user)
            ->get(route('hostgroups.index'))
            ->assertSuccessful()
            ->assertViewIs('hostgroup.index');
    }

    /** @test */
    public function it_should_show_the_new_hosts_group_form(): void
    {
        $hosts = Host::factory()
            ->count(3)
            ->create();

        $this
            ->actingAs($this->user)
            ->get(route('hostgroups.create'))
            ->assertSuccessful()
            ->assertViewIs('hostgroup.create')
            ->assertViewHas('hosts', $hosts->pluck('hostname', 'id'));
    }

    /** @test */
    public function it_should_create_a_new_hosts_group(): void
    {
        // Create some hosts to test the membership.
        $hosts = Host::factory()
            ->count(2)
            ->create();

        /** @var Hostgroup $want */
        $want = Hostgroup::factory()->make();

        $this
            ->actingAs($this->user)
            ->post(route('hostgroups.store'), [
                'name' => $want->name,
                'description' => $want->description,
                'hosts' => $hosts->pluck('id')->toArray(),
            ])
            ->assertRedirect(route('hostgroups.index'))
            ->assertSessionHasNoErrors();

        $group = Hostgroup::query()
            ->where('name', $want->name)
            ->where('description', $want->description)
            ->first();

        $this->assertInstanceOf(Hostgroup::class, $group);

        $this->assertCount(count($hosts), $group->hosts);
    }

    /**
     * @test
     * @dataProvider provideWrongDataForHostsGroupCreation
     */
    public function it_should_return_errors_when_creating_a_new_hosts_group(
        array $data,
        array $errors,
    ): void {
        // Group to validate unique rules...
        Hostgroup::factory()->create([
            'name' => 'foo',
        ]);

        /** @var Hostgroup $want */
        $want = Hostgroup::factory()->make();

        $formData = [
            'name' => $data['name'] ?? $want->name,
            'description' => $data['description'] ?? $want->description,
            'hosts' => $data['hosts'] ?? [],
        ];

        $this
            ->actingAs($this->user)
            ->post(route('hostgroups.store'), $formData)
            ->assertSessionHasErrors($errors);

        $this->assertDatabaseMissing(Hostgroup::class, [
            'name' => $formData['name'],
            'description' => $formData['description'],
        ]);
    }

    public function provideWrongDataForHostsGroupCreation(): Generator
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
            'errors' => ['hosts.*'],
        ];
    }

    /** @test */
    public function it_should_show_the_edit_hosts_group_form(): void
    {
        $group = Hostgroup::factory()->create();

        $hosts = Host::factory()
            ->count(3)
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
    public function it_should_update_the_hosts_group(): void
    {
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
                'name' => $want->name,
                'description' => $want->description,
                'hosts' => $hosts->pluck('id')->toArray(),
            ])
            ->assertRedirect(route('hostgroups.edit', [$group]))
            ->assertSessionHasNoErrors();

        $group->refresh();

        $this->assertModelExists($group);

        $this->assertCount(count($hosts), $group->hosts);
    }

    /**
     * @test
     * @dataProvider provideWrongDataForHostsGroupModification
     */
    public function it_should_return_errors_when_updating_the_hosts_group(
        array $data,
        array $errors,
    ): void {
        // Group to validate unique rules...
        Hostgroup::factory()->create([
            'name' => 'foo',
        ]);

        /** @var Hostgroup $group */
        $group = Hostgroup::factory()->create();

        /** @var Hostgroup $want */
        $want = Hostgroup::factory()->make();

        $formData = [
            'name' => $data['name'] ?? $want->name,
            'description' => $data['description'] ?? $want->description,
            'hosts' => $data['hosts'] ?? [],
        ];

        $this
            ->actingAs($this->user)
            ->put(route('hostgroups.update', $group), $formData)
            ->assertSessionHasErrors($errors);

        $this->assertDatabaseMissing(Hostgroup::class, [
            'id' => $group->id,
            'name' => $formData['name'],
            'description' => $formData['description'],
        ]);

        $group->refresh();

        $this->assertCount(0, $group->hosts);
    }

    public function provideWrongDataForHostsGroupModification(): Generator
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
            'errors' => ['hosts.*'],
        ];
    }

    /** @test */
    public function it_should_delete_the_hosts_group(): void
    {
        /** @var Hostgroup $group */
        $group = Hostgroup::factory()->create();

        $this
            ->actingAs($this->user)
            ->delete(route('hostgroups.destroy', $group))
            ->assertRedirect(route('hostgroups.index'))
            ->assertSessionHas('success');

        $this->assertModelMissing($group);
    }

    /** @test */
    public function it_should_return_error_for_non_AJAX_requests(): void
    {
        $this
            ->actingAs($this->user)
            ->get(route('hostgroups.data'))
            ->assertForbidden();
    }

    /** @test */
    public function it_should_return_a_JSON_with_the_data(): void
    {
        $groups = Hostgroup::factory()
            ->count(3)
            ->create();

        $this
            ->actingAs($this->user)
            ->ajaxGet(route('hostgroups.data'))
            ->assertSuccessful()
            ->assertJsonCount(count($groups), 'data')
            ->assertJsonStructure([
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
    }
}
