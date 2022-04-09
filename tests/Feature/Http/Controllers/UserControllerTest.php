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

        $this->disablePermissionsCheck();

        // Roles and Permission are used when creating/updating users as part of its data.
        $this->loadRolesAndPermissions();

        $this->user = User::factory()
            ->create();
    }

    /** @test */
    public function it_should_show_the_index_view(): void
    {
        $this
            ->actingAs($this->user)
            ->get(route('users.index'))
            ->assertSuccessful()
            ->assertViewIs('user.index');
    }

    /** @test */
    public function it_should_show_the_new_user_form(): void
    {
        $this
            ->actingAs($this->user)
            ->get(route('users.create'))
            ->assertSuccessful()
            ->assertViewIs('user.create');
    }

    /** @test */
    public function it_should_create_a_new_user(): void
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
            ->assertRedirect(route('users.index'))
            ->assertSessionHasNoErrors();

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
    public function it_should_return_errors_when_creating_a_new_user(
        array $data,
        array $errors
    ): void {
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

        $response = $this
            ->actingAs($this->user)
            ->post(route('users.store'), $formData)
            ->assertSessionHasErrors($errors);

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
    public function it_should_show_the_edit_user_form(): void
    {
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
    public function it_should_update_the_user(): void
    {
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
            ->assertSessionHasNoErrors();

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
    public function it_should_return_errors_when_updating_the_user(
        array $data,
        array $errors
    ): void {
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
            ->assertSessionHasErrors($errors);

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
    public function it_should_delete_the_user(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this
            ->actingAs($this->user)
            ->delete(route('users.destroy', $user))
            ->assertRedirect(route('users.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted($user);
    }

    /** @test */
    public function it_should_return_error_when_deleting_itself(): void
    {
        $this
            ->actingAs($this->user)
            ->delete(route('users.destroy', $this->user))
            ->assertSessionHasErrors();

        $this->assertModelExists($this->user);
    }

    /** @test */
    public function it_should_return_error_for_non_AJAX_requests(): void
    {
        $this
            ->actingAs($this->user)
            ->get(route('users.data'))
            ->assertForbidden();
    }

    /** @test */
    public function it_should_return_a_JSON_with_the_data(): void
    {
        $users = User::factory()
            ->count(3)
            ->create([
                'enabled' => 'true',
            ]);

        $this
            ->actingAs($this->user)
            ->ajaxGet(route('users.data'))
            ->assertSuccessful()
            ->assertJsonCount(count($users) + 1, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'username',
                        'email',
                    ],
                ],
            ]);
    }
}
