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
use App\Enums\Roles;
use App\Models\User;
use Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tests\Traits\InteractsWithPermissions;

class UserControllerTest extends TestCase
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
            ->get(route('users.index'))
            ->assertForbidden();
    }

    /** @test */
    public function viewers_should_see_the_index_view(): void
    {
        $this->user->givePermissionTo(Permissions::ViewUsers);

        $this
            ->actingAs($this->user)
            ->get(route('users.index'))
            ->assertSuccessful()
            ->assertViewIs('user.index');
    }

    /** @test */
    public function users_should_not_see_the_new_user_form(): void
    {
        $this
            ->actingAs($this->user)
            ->get(route('users.create'))
            ->assertForbidden();
    }

    /** @test */
    public function editors_should_see_the_new_user_form(): void
    {
        $this->user->givePermissionTo(Permissions::EditUsers);

        $this
            ->actingAs($this->user)
            ->get(route('users.create'))
            ->assertSuccessful()
            ->assertViewIs('user.create');
    }

    /** @test */
    public function users_should_not_create_users(): void
    {
        /** @var User $want */
        $want = User::factory()->make();

        $this
            ->actingAs($this->user)
            ->post(route('users.store'), [
                'username' => $want->username,
                'email' => $want->email,
                'password' => 'secret123',
                'password_confirmation' => 'secret123',
                'role' => Roles::Operator,
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing(User::class, [
            'username' => $want->username,
            'email' => $want->email,
        ]);
    }

    /** @test */
    public function editors_should_create_users(): void
    {
        $this->user->givePermissionTo(Permissions::EditUsers);

        /** @var User $want */
        $want = User::factory()->make();

        $this
            ->actingAs($this->user)
            ->post(route('users.store'), [
                'username' => $want->username,
                'email' => $want->email,
                'password' => 'secret123',
                'password_confirmation' => 'secret123',
                'role' => Roles::Operator,
            ])
            ->assertRedirect(route('users.index'))
            ->assertValid();

        $user = User::query()
            ->where('username', $want->username)
            ->where('email', $want->email)
            ->first();

        $this->assertInstanceOf(User::class, $user);

        $this->assertEquals(Roles::Operator, $user->role);
    }

    /**
     * @test
     * @dataProvider provideWrongDataForUserCreation
     */
    public function editors_should_get_errors_when_creating_users_with_wrong_data(
        array $data,
        array $errors
    ): void {
        $this->user->givePermissionTo(Permissions::EditUsers);

        // User to validate unique rules...
        User::factory()->create([
            'username' => 'john',
            'email' => 'john.doe@domain.local',
        ]);

        /** @var User $want */
        $want = User::factory()->make();

        $formData = [
            'username' => $data['username'] ?? $want->username,
            'email' => $data['email'] ?? $want->email,
            'password' => $data['password'] ?? $want->password,
            'password_confirmation' => $data['password_confirmation'] ?? $want->password,
            'role' => $data['role'] ?? null,
        ];

        $this
            ->actingAs($this->user)
            ->post(route('users.store'), $formData)
            ->assertInvalid($errors);

        $this->assertDatabaseMissing(User::class, [
            'username' => $formData['username'],
            'email' => $formData['email'],
        ]);
    }

    public function provideWrongDataForUserCreation(): Generator
    {
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

        yield 'username ! a username' => [
            'data' => [
                'username' => 'u$ern4me',
            ],
            'errors' => ['username'],
        ];

        yield 'username is taken' => [
            'data' => [
                'username' => 'john',
            ],
            'errors' => ['username'],
        ];

        yield 'email is empty' => [
            'data' => [
                'email' => '',
            ],
            'errors' => ['email'],
        ];

        yield 'email ! an email' => [
            'data' => [
                'email' => 'is-not-an-email',
            ],
            'errors' => ['email'],
        ];

        yield 'email is taken' => [
            'data' => [
                'email' => 'john.doe@domain.local',
            ],
            'errors' => ['email'],
        ];

        yield 'password is empty' => [
            'data' => [
                'password' => '',
            ],
            'errors' => ['password'],
        ];

        yield 'password ! long enough' => [
            'data' => [
                'password' => '1234',
            ],
            'errors' => ['password'],
        ];

        yield 'password ! confirmed' => [
            'data' => [
                'password' => 'verySecretPassword',
                'password_confirmation' => 'notSoSecretPassword',
            ],
            'errors' => ['password'],
        ];

        yield 'role ! a role' => [
            'data' => [
                'role' => 'non-existent-role',
            ],
            'errors' => ['role'],
        ];
    }

    /** @test */
    public function users_should_not_see_other_edit_user_form(): void
    {
        $user = User::factory()->create();
        $user->assignRole(Roles::Operator);

        $this
            ->actingAs($this->user)
            ->get(route('users.edit', $user))
            ->assertForbidden();
    }

    /** @test */
    public function users_should_see_their_own_edit_user_form(): void
    {
        $user = User::factory()->create();
        $user->assignRole(Roles::Operator);

        $this
            ->actingAs($user)
            ->get(route('users.edit', $user))
            ->assertSuccessful()
            ->assertViewIs('user.edit')
            ->assertViewHas('user', $user);
    }

    /** @test */
    public function editors_should_see_the_edit_user_form(): void
    {
        $this->user->givePermissionTo(Permissions::EditUsers);

        $user = User::factory()->create();
        $user->assignRole(Roles::Operator);

        $this
            ->actingAs($this->user)
            ->get(route('users.edit', $user))
            ->assertSuccessful()
            ->assertViewIs('user.edit')
            ->assertViewHas('user', $user);
    }

    /** @test */
    public function users_should_not_update_other_users(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $user->assignRole(Roles::Auditor);

        $this
            ->actingAs($this->user)
            ->put(route('users.update', $user), [])
            ->assertForbidden();

        $this->assertModelExists($user);
    }

    /** @test */
    public function users_should_change_their_own_credentials(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'enabled' => true,
            'password' => bcrypt('veryS3cretP4ssword'),
        ]);
        $user->assignRole(Roles::Auditor);

        $this
            ->actingAs($user)
            ->put(route('users.update', $user), [
                'email' => $user->email,
                'enabled' => $user->enabled,
                'current_password' => 'veryS3cretP4ssword',
                'password' => 'new-password-123',
                'password_confirmation' => 'new-password-123',
                'role' => Roles::Auditor,
            ])
            ->assertRedirect(route('users.index'))
            ->assertValid();

        $this->assertModelExists($user);

        $user->refresh();

        $this->assertTrue(Hash::check('new-password-123', $user->password));
    }

    /** @test */
    public function users_should_not_change_its_own_credentials_without_the_current_password(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'enabled' => true,
            'password' => bcrypt('veryS3cretP4ssword'),
        ]);
        $user->assignRole(Roles::Auditor);

        $this
            ->actingAs($user)
            ->put(route('users.update', $user), [
                'email' => $user->email,
                'enabled' => $user->enabled,
                'current_password' => '',
                'password' => 'new-password-123',
                'password_confirmation' => 'new-password-123',
                'role' => Roles::Auditor,
            ])
            ->assertInvalid('current_password');

        $this->assertModelExists($user);

        $user->refresh();

        $this->assertTrue(Hash::check('veryS3cretP4ssword', $user->password));
    }

    /** @test */
    public function users_should_update_themselves(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'enabled' => true,
        ]);
        $user->assignRole(Roles::Auditor);

        /** @var User $want */
        $want = User::factory()->make();

        $this
            ->actingAs($user)
            ->put(route('users.update', $user), [
                'email' => $want->email,
                'enabled' => $user->enabled,
                'role' => Roles::Auditor,
            ])
            ->assertRedirect(route('users.index'))
            ->assertValid();

        $this->assertDatabaseHas(User::class, [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $want->email,
        ]);

        $user->refresh();

        $this->assertEquals(Roles::Auditor, $user->role);
    }

    /** @test */
    public function users_should_not_update_neither_status_nor_role(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'enabled' => true,
        ]);
        $user->assignRole(Roles::Auditor);

        $this
            ->actingAs($user)
            ->put(route('users.update', $user), [
                'email' => $user->email,
                'enabled' => false,
                'role' => Roles::Operator,
            ])
            ->assertInvalid(['enabled', 'role']);

        $this->assertDatabaseHas(User::class, [
            'id' => $user->id,
            'username' => $user->username,
            'enabled' => $user->enabled,
        ]);

        $user->refresh();

        $this->assertEquals(Roles::Auditor, $user->role);
    }

    /** @test */
    public function editors_should_update_users(): void
    {
        $this->user->givePermissionTo(Permissions::EditUsers);

        /** @var User $user */
        $user = User::factory()->create([
            'enabled' => true,
        ]);
        $user->assignRole(Roles::Auditor);

        /** @var User $want */
        $want = User::factory()->make([
            'enabled' => false,
        ]);

        $this
            ->actingAs($this->user)
            ->put(route('users.update', $user), [
                'email' => $want->email,
                'enabled' => $want->enabled,
                'password' => 'new-password-123',
                'password_confirmation' => 'new-password-123',
                'role' => Roles::Operator,
            ])
            ->assertRedirect(route('users.index'))
            ->assertValid();

        $this->assertDatabaseHas(User::class, [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $want->email,
            'enabled' => $want->enabled,
        ]);

        $user->refresh();

        $this->assertTrue(Hash::check('new-password-123', $user->password));

        $this->assertEquals(Roles::Operator, $user->role);
    }

    /**
     * @test
     * @dataProvider provideWrongDataForUserModification
     */
    public function editors_should_get_errors_when_updating_users_with_wrong_data(
        array $data,
        array $errors
    ): void {
        $this->user->givePermissionTo(Permissions::EditUsers);

        // User to validate unique rules...
        User::factory()->create([
            'username' => 'john',
            'email' => 'john.doe@domain.local',
        ]);

        /** @var User $user */
        $user = User::factory()->create([
            'password' => Hash::make('veryS3cr3t'),
        ]);
        $user->assignRole(Roles::Admin);

        $formData = [
            'email' => $data['email'] ?? $user->email,
            'password' => $data['password'] ?? $user->password,
            'password_confirmation' => $data['password_confirmation'] ?? $user->password,
            'role' => $data['role'] ?? $user->role,
            'enabled' => $data['enabled'] ?? $user->enabled,
        ];

        $this
            ->actingAs($this->user)
            ->put(route('users.update', $user), $formData)
            ->assertInvalid($errors);

        $this->assertDatabaseHas(User::class, [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'enabled' => $user->enabled,
        ]);

        $user->refresh();

        $this->assertTrue(Hash::check('veryS3cr3t', $user->password));

        $this->assertEquals(Roles::Admin, $user->role);
    }

    public function provideWrongDataForUserModification(): Generator
    {
        yield 'email is empty' => [
            'data' => [
                'email' => '',
            ],
            'errors' => ['email'],
        ];

        yield 'email ! an email' => [
            'data' => [
                'email' => 'is-not-an-email',
            ],
            'errors' => ['email'],
        ];

        yield 'email is taken' => [
            'data' => [
                'email' => 'john.doe@domain.local',
            ],
            'errors' => ['email'],
        ];

        yield 'password ! long enough' => [
            'data' => [
                'password' => '1234',
            ],
            'errors' => ['password'],
        ];

        yield 'password ! confirmed' => [
            'data' => [
                'password' => 'verySecretPassword',
                'password_confirmation' => 'notSoSecretPassword',
            ],
            'errors' => ['password'],
        ];

        yield 'role ! a role' => [
            'data' => [
                'role' => 'non-existent-role',
            ],
            'errors' => ['role'],
        ];

        yield 'enabled ! valid' => [
            'data' => [
                'enabled' => 'non-boolean',
            ],
            'errors' => ['enabled'],
        ];
    }

    /** @test */
    public function users_should_not_delete_users(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this
            ->actingAs($this->user)
            ->delete(route('users.destroy', $user))
            ->assertForbidden();

        $this->assertModelExists($user);
    }

    /** @test */
    public function users_should_not_delete_themselves(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $user->givePermissionTo(Permissions::DeleteUsers);

        $this
            ->actingAs($user)
            ->delete(route('users.destroy', $user))
            ->assertForbidden();

        $this->assertModelExists($user);
    }

    /** @test */
    public function eliminators_should_delete_users(): void
    {
        $this->user->givePermissionTo(Permissions::DeleteUsers);

        /** @var User $user */
        $user = User::factory()->create();

        $this
            ->actingAs($this->user)
            ->delete(route('users.destroy', $user))
            ->assertRedirect(route('users.index'));

        $this->assertSoftDeleted($user);
    }
}
