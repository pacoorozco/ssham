<?php
/*
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2022 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2017 - 2022 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/ssham
 */

namespace Tests\Feature\Models;

use App\Enums\Roles;
use App\Models\User;
use Tests\Feature\InteractsWithPermissions;
use Tests\Feature\TestCase;

class UserTest extends TestCase
{
    use InteractsWithPermissions;

    public function setUp(): void
    {
        parent::setUp();

        // Roles and Permission are used when creating/updating users as part of its data.
        $this->loadRolesAndPermissions();
    }

    /**
     * @test
     *
     * @dataProvider provideRoleInfo
     */
    public function it_returns_true_when_user_is_super_admin(Roles $role, bool $want): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $user->assignRole($role->value);

        $this->assertEquals($want, $user->isSuperAdmin());
    }

    public static function provideRoleInfo()
    {
        yield 'Auditor is not Super Admin' => [
            'role' => Roles::Auditor(),
            'expected' => false,
        ];

        yield 'Operator is not Super Admin' => [
            'role' => Roles::Operator(),
            'expected' => false,
        ];

        yield 'Admin is not Super Admin' => [
            'role' => Roles::Admin(),
            'expected' => false,
        ];

        yield 'SuperAdmin is Super Admin' => [
            'role' => Roles::SuperAdmin(),
            'expected' => true,
        ];
    }
}
