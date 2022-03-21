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

namespace Tests\Feature\Actions;

use App\Actions\CreateUser;
use App\Enums\Roles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use Tests\Traits\InteractsWithPermissions;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;
    use InteractsWithPermissions;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        // Roles and Permission are used when creating/updating users as part of its data.
        $this->loadRolesAndPermissions();
    }

    /** @test */
    public function it_can_create_a_user(): void
    {
        /** @var \App\Models\User $testUser */
        $testUser = User::factory()->make();

        $action = app(CreateUser::class);

        $user = $action([
            'username' => $testUser->username,
            'email' => $testUser->email,
            'password' => 'SuperSecr3tP4ssword',
            'password_confirmation' => 'SuperSecr3tP4ssword',
            'role' => Roles::Operator,
        ]);


        $this->assertDatabaseHas('users', [
            'username' => $testUser->username,
            'email' => $testUser->email,
        ]);
        $this->assertTrue(Hash::check('SuperSecr3tP4ssword', $user->password));
        $this->assertEquals(Roles::Operator, $user->role);
    }

    /**
     * @test
     * @dataProvider providesWrongData
     */
    public function it_is_invalid_with_wrong_data_sets(array $data, array $errors): void
    {
        $action = app(CreateUser::class);

        // User to validate unique rules...
        User::factory()->create([
            'username' => 'john',
            'email' => 'john.doe@domain.local',
        ]);

        /** @var User $want */
        $want = User::factory()->make($data);

        try {
            $action([
                'username' => $want->username,
                'email' => $want->email,
                'password' => $want->password,
                'password_confirmation' => $data['password_confirmation'] ?? $want->password,
                'role' => $data['role'] ?? '',
            ]);
        } catch (ValidationException $exception) {
            $validationErrors = $exception->errors();

            $this->assertCount(count($errors), $validationErrors);

            foreach ($errors as $error) {
                $this->assertNotNull($validationErrors[$error]);
            }
        }

        $this->assertDatabaseMissing('users', [
            'username' => $want->username,
            'email' => $want->email,
        ]);
    }

    public function providesWrongData(): array
    {
        return [
            'username is empty' => [
                'data' => [
                    'username' => '',
                ],
                'errors' => ['username'],
            ],
            'username > 255 chars' => [
                'data' => [
                    'username' => '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345',
                ],
                'errors' => ['username'],
            ],
            'username ! a username' => [
                'data' => [
                    'username' => 'u$ern4me',
                ],
                'errors' => ['username'],
            ],
            'username is taken' => [
                'data' => [
                    'username' => 'john',
                ],
                'errors' => ['username'],
            ],
            'email is empty' => [
                'data' => [
                    'email' => '',
                ],
                'errors' => ['email'],
            ],
            'email ! an email' => [
                'data' => [
                    'email' => 'is-not-an-email',
                ],
                'errors' => ['email'],
            ],
            'email is taken' => [
                'data' => [
                    'email' => 'john.doe@domain.local',
                ],
                'errors' => ['email'],
            ],
            'password is empty' => [
                'data' => [
                    'password' => '',
                ],
                'errors' => ['password'],
            ],
            'password ! long enough' => [
                'data' => [
                    'password' => '1234',
                ],
                'errors' => ['password'],
            ],
            'password ! confirmed' => [
                'data' => [
                    'password' => 'verySecretPassword',
                    'password_confirmation' => 'notSoSecretPassword',
                ],
                'errors' => ['password'],
            ],
            'role ! a role' => [
                'data' => [
                    'role' => 'non-existent-role',
                ],
                'errors' => ['role'],
            ],
        ];
    }
}
