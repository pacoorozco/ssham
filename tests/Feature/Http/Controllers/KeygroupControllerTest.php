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

use App\Enums\Permissions;
use App\Models\Key;
use App\Models\Keygroup;
use App\Models\User;
use Generator;
use Tests\Feature\InteractsWithPermissions;
use Tests\Feature\TestCase;

class KeygroupControllerTest extends TestCase
{
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
            ->get(route('keygroups.index'))
            ->assertForbidden();
    }

    /** @test */
    public function viewers_should_see_the_index_view(): void
    {
        $this->user->givePermissionTo(Permissions::ViewKeys);

        $this
            ->actingAs($this->user)
            ->get(route('keygroups.index'))
            ->assertSuccessful()
            ->assertViewIs('keygroup.index');
    }

    /** @test */
    public function users_should_not_see_any_keys_group(): void
    {
        $group = Keygroup::factory()->create();

        $this
            ->actingAs($this->user)
            ->get(route('keygroups.show', $group))
            ->assertForbidden();
    }

    /** @test */
    public function viewers_should_see_any_keys_group(): void
    {
        $this->user->givePermissionTo(Permissions::ViewKeys);

        $group = Keygroup::factory()->create();

        $this
            ->actingAs($this->user)
            ->get(route('keygroups.show', $group))
            ->assertSuccessful()
            ->assertViewIs('keygroup.show')
            ->assertViewHas('keygroup', $group);
    }

    /** @test */
    public function users_should_not_see_the_new_group_form(): void
    {
        Key::factory()->create();

        $this
            ->actingAs($this->user)
            ->get(route('keygroups.create'))
            ->assertForbidden();
    }

    /** @test */
    public function editors_should_see_the_new_group_form(): void
    {
        $this->user->givePermissionTo(Permissions::EditKeys);

        $keys = Key::factory()
            ->count(2)
            ->create();

        $this
            ->actingAs($this->user)
            ->get(route('keygroups.create'))
            ->assertSuccessful()
            ->assertViewIs('keygroup.create')
            ->assertViewHas('keys', $keys->pluck('username', 'id'));
    }

    /** @test */
    public function users_should_not_create_groups(): void
    {
        /** @var Keygroup $group */
        $group = Keygroup::factory()->make();

        $this
            ->actingAs($this->user)
            ->post(route('keygroups.store'), [
                'name' => $group->name,
                'description' => $group->description,
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing(Keygroup::class, [
            'name' => $group->name,
            'description' => $group->description,
        ]);
    }

    /** @test */
    public function editors_should_create_groups(): void
    {
        $this->user->givePermissionTo(Permissions::EditKeys);

        // Create some keys to test the membership.
        $keys = Key::factory()
            ->count(2)
            ->create();

        /** @var Keygroup $want */
        $want = Keygroup::factory()->make();

        $this
            ->actingAs($this->user)
            ->post(route('keygroups.store'), [
                'name' => $want->name,
                'description' => $want->description,
                'keys' => $keys->pluck('id')->toArray(),
            ])
            ->assertRedirect(route('keygroups.index'))
            ->assertValid();

        $group = Keygroup::query()
            ->where('name', $want->name)
            ->where('description', $want->description)
            ->first();

        $this->assertInstanceOf(Keygroup::class, $group);

        $this->assertCount(count($keys), $group->keys);
    }

    /**
     * @test
     * @dataProvider provideWrongDataForGroupCreation
     */
    public function editors_should_get_errors_when_creating_groups_with_wrong_data(
        array $data,
        array $errors
    ): void {
        $this->user->givePermissionTo(Permissions::EditKeys);

        // Group to validate unique rules...
        Keygroup::factory()->create([
            'name' => 'foo',
        ]);

        /** @var Keygroup $want */
        $want = Keygroup::factory()->make();

        $formData = [
            'name' => $data['name'] ?? $want->name,
            'description' => $data['description'] ?? $want->description,
            'keys' => $data['keys'] ?? [],
        ];

        $this
            ->actingAs($this->user)
            ->post(route('keygroups.store'), $formData)
            ->assertInvalid($errors);

        $this->assertDatabaseMissing(Keygroup::class, [
            'name' => $formData['name'],
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

        yield 'keys ! a key' => [
            'data' => [
                'keys' => [1],
            ],
            'errors' => ['keys'],
        ];
    }

    /** @test */
    public function users_should_not_see_edit_group_form(): void
    {
        $group = Keygroup::factory()->create();

        // Create some keys to test the membership.
        Key::factory()->create();

        $this
            ->actingAs($this->user)
            ->get(route('keygroups.edit', $group))
            ->assertForbidden();
    }

    /** @test */
    public function editors_should_see_the_edit_group_form(): void
    {
        $this->user->givePermissionTo(Permissions::EditKeys);

        $group = Keygroup::factory()->create();

        // Create some keys to test the membership.
        $keys = Key::factory()
            ->count(2)
            ->create();

        $this
            ->actingAs($this->user)
            ->get(route('keygroups.edit', $group))
            ->assertSuccessful()
            ->assertViewIs('keygroup.edit')
            ->assertViewHas('keygroup', $group)
            ->assertViewHas('keys', $keys->pluck('username', 'id'));
    }

    /** @test */
    public function editors_should_update_groups(): void
    {
        $this->user->givePermissionTo(Permissions::EditKeys);

        // Create some keys to test the membership.
        $keys = Key::factory()
            ->count(2)
            ->create();

        /** @var Keygroup $group */
        $group = Keygroup::factory()->create();

        /** @var Keygroup $want */
        $want = Keygroup::factory()->make();

        $this
            ->actingAs($this->user)
            ->put(route('keygroups.update', $group), [
                'name' => $want->name,
                'description' => $want->description,
                'keys' => $keys->pluck('id')->toArray(),
            ])
            ->assertRedirect(route('keygroups.edit', $group))
            ->assertValid();

        $group->refresh();

        $this->assertModelExists($group);

        $this->assertCount(count($keys), $group->keys);
    }

    /**
     * @test
     * @dataProvider provideWrongDataForGroupModification
     */
    public function editors_should_get_errors_when_updating_groups_with_wrong_data(
        array $data,
        array $errors
    ): void {
        $this->user->givePermissionTo(Permissions::EditKeys);

        // Group to validate unique rules...
        Keygroup::factory()->create([
            'name' => 'foo',
        ]);

        /** @var Keygroup $group */
        $group = Keygroup::factory()->create();

        /** @var Keygroup $want */
        $want = Keygroup::factory()->make();

        $formData = [
            'name' => $data['name'] ?? $want->name,
            'description' => $data['description'] ?? $want->description,
            'keys' => $data['keys'] ?? [],
        ];

        $this
            ->actingAs($this->user)
            ->put(route('keygroups.update', $group), $formData)
            ->assertInvalid($errors);

        $this->assertDatabaseMissing(Keygroup::class, [
            'id' => $group->id,
            'name' => $formData['name'],
            'description' => $formData['description'],
        ]);

        $group->refresh();

        $this->assertCount(0, $group->keys);
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

        yield 'keys ! a key' => [
            'data' => [
                'keys' => [1],
            ],
            'errors' => ['keys'],
        ];
    }

    /** @test */
    public function users_should_not_delete_groups(): void
    {
        $group = Keygroup::factory()->create();

        $this
            ->actingAs($this->user)
            ->delete(route('keygroups.destroy', $group))
            ->assertForbidden();
    }

    /** @test */
    public function eliminators_should_delete_groups(): void
    {
        $this->user->givePermissionTo(Permissions::DeleteKeys);

        $group = Keygroup::factory()->create();

        $this
            ->actingAs($this->user)
            ->delete(route('keygroups.destroy', $group))
            ->assertRedirect(route('keygroups.index'));

        $this->assertModelMissing($group);
    }
}
