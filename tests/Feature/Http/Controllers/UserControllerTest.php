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
    public function index_method_should_return_proper_view(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('users.index'));

        $response->assertSuccessful();
        $response->assertViewIs('user.index');
    }

    /** @test */
    public function create_method_should_return_proper_view(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('users.create'));

        $response->assertSuccessful();
        $response->assertViewIs('user.create');
    }

    /** @test */
    public function it_creates_a_new_user(): void
    {
        /** @var User $want */
        $want = User::factory()->make();

        $response = $this
            ->actingAs($this->user)
            ->post(route('users.store'), [
                'username' => $want->username,
                'email' => $want->email,
                'password' => 'secret123',
                'password_confirmation' => 'secret123',
                'role' => Roles::Operator,
            ]);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', [
            'username' => $want->username,
            'email' => $want->email,
        ]);
    }

    /**
     * @test
     * @dataProvider provideWrongDataForUserCreation
     */
    public function it_returns_errors_when_creating_a_new_user(
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
            ->post(route('users.store'), $formData);

        $response->assertSessionHasErrors($errors);

        $this->assertDatabaseMissing('users', [
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
    public function edit_method_should_return_proper_view(): void
    {
        $testUser = User::factory()
            ->create();
        $testUser->assignRole(Roles::Operator);

        $response = $this
            ->actingAs($this->user)
            ->get(route('users.edit', $testUser));

        $response->assertSuccessful();
        $response->assertViewIs('user.edit');
        $response->assertViewHas('user', $testUser);
    }

    /** @test */
    public function it_updates_the_user(): void
    {
        /** @var User $testUser */
        $testUser = User::factory()->create([
            'enabled' => true,
        ]);
        $testUser->assignRole(Roles::Auditor);

        /** @var User $want */
        $want = User::factory()->make([
            'enabled' => false,
        ]);

        $response = $this
            ->actingAs($this->user)
            ->put(route('users.update', $testUser), [
                'email' => $want->email,
                'enabled' => $want->enabled,
                'password' => 'new-password-123',
                'password_confirmation' => 'new-password-123',
                'role' => Roles::Operator,
            ]);

        $response->assertRedirect(route('users.index'));

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'id' => $testUser->id,
            'username' => $testUser->username,
            'email' => $want->email,
            'enabled' => $want->enabled,
        ]);

        $testUser->refresh();

        $this->assertTrue(Hash::check('new-password-123', $testUser->password));

        $this->assertEquals(Roles::Operator, $testUser->role);
    }

    /**
     * @test
     * @dataProvider provideWrongDataForUserModification
     */
    public function it_returns_errors_when_updating_a_user(
        array $data,
        array $errors
    ): void {
        // User to validate unique rules...
        User::factory()->create([
            'username' => 'john',
            'email' => 'john.doe@domain.local',
        ]);

        /** @var User $testUser */
        $testUser = User::factory()->create([
            'password' => Hash::make('veryS3cr3t'),
        ]);
        $testUser->assignRole(Roles::Admin);

        $formData = [
            'email' => $data['email'] ?? $testUser->email,
            'password' => $data['password'] ?? $testUser->password,
            'password_confirmation' => $data['password_confirmation'] ?? $testUser->password,
            'role' => $data['role'] ?? $testUser->role,
            'enabled' => $data['enabled'] ?? $testUser->enabled,
        ];

        $response = $this
            ->actingAs($this->user)
            ->put(route('users.update', $testUser), $formData);

        $response->assertSessionHasErrors($errors);

        $this->assertDatabaseHas('users', [
            'id' => $testUser->id,
            'username' => $testUser->username,
            'email' => $testUser->email,
            'enabled' => $testUser->enabled,
        ]);

        $testUser->refresh();

        $this->assertTrue(Hash::check('veryS3cr3t', $testUser->password));

        $this->assertEquals(Roles::Admin, $testUser->role);
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

        yield 'enable ! valid' => [
            'data' => [
                'enabled' => 'non-boolean',
            ],
            'errors' => ['enabled'],
        ];
    }

    /** @test */
    public function destroy_method_should_return_success_and_delete_user(): void
    {
        $testUser = User::factory()
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->delete(route('users.destroy', $testUser));

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');
        $this->assertSoftDeleted($testUser);
    }

    /** @test */
    public function data_method_should_return_error_when_not_ajax(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('users.data'));

        $response->assertForbidden();
    }

    /** @test */
    public function data_method_should_return_data(): void
    {
        $users = User::factory()
            ->count(3)
            ->create([
                'enabled' => 'true',
            ]);

        $response = $this
            ->actingAs($this->user)
            ->ajaxGet(route('users.data'));

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'username',
                    'email',
                ],
            ],
        ]);
        foreach ($users as $testUser) {
            $response->assertJsonFragment([
                'username' => $testUser->username,
                'email' => $testUser->email,
            ]);
        }
    }
}
