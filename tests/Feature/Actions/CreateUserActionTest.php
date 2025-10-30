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

use App\Actions\CreateUserAction;
use App\Enums\Roles;
use Generator;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\InteractsWithPermissions;
use Tests\Feature\TestCase;

final class CreateUserActionTest extends TestCase
{
    use InteractsWithPermissions;

    protected function setUp(): void
    {
        parent::setUp();

        // Roles and Permission are used when creating/updating users as part of its data.
        $this->loadRolesAndPermissions();
    }

    #[Test]
    #[DataProvider('providesUserData')]
    public function it_can_create_a_user(
        string $username,
        string $email,
        string $password,
        ?string $role,
        string $wantRole,
    ): void {
        $action = app(CreateUserAction::class);

        $user = $action(
            username: $username,
            email: $email,
            password: $password,
            role: $role
        );

        $this->assertDatabaseHas('users', [
            'username' => $username,
            'email' => $email,
        ]);

        $this->assertTrue(Hash::check($password, $user->password));

        $this->assertEquals($wantRole, $user->role->value);
    }

    public static function providesUserData(): Generator
    {
        yield 'role = SuperAdmin' => [
            'username' => 'john',
            'email' => 'john.doe@nowhere.local',
            'password' => 'verySecret',
            'role' => Roles::SuperAdmin->value,
            'wantRole' => Roles::SuperAdmin->value,
        ];

        yield 'role = Admin' => [
            'username' => 'john',
            'email' => 'john.doe@nowhere.local',
            'password' => 'verySecret',
            'role' => Roles::Admin->value,
            'wantRole' => Roles::Admin->value,
        ];

        yield 'role = Operator' => [
            'username' => 'john',
            'email' => 'john.doe@nowhere.local',
            'password' => 'verySecret',
            'role' => Roles::Operator->value,
            'wantRole' => Roles::Operator->value,
        ];

        yield 'role = Auditor' => [
            'username' => 'john',
            'email' => 'john.doe@nowhere.local',
            'password' => 'verySecret',
            'role' => Roles::Auditor->value,
            'wantRole' => Roles::Auditor->value,
        ];

        yield 'empty role' => [
            'username' => 'john',
            'email' => 'john.doe@nowhere.local',
            'password' => 'verySecret',
            'role' => null,
            'wantRole' => Roles::Auditor->value,
        ];

        yield 'invalid role' => [
            'username' => 'john',
            'email' => 'john.doe@nowhere.local',
            'password' => 'verySecret',
            'role' => 'non-existent-role',
            'wantRole' => Roles::Auditor->value,
        ];
    }
}
