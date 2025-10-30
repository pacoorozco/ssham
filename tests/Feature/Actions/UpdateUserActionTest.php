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

use App\Actions\UpdateUserAction;
use App\Enums\Roles;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\InteractsWithPermissions;
use Tests\Feature\TestCase;

final class UpdateUserActionTest extends TestCase
{
    use InteractsWithPermissions;

    protected function setUp(): void
    {
        parent::setUp();

        // Roles and Permission are used when creating/updating users as part of its data.
        $this->loadRolesAndPermissions();
    }

    #[Test]
    public function it_can_update_a_user(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $user->assignRole(Roles::Auditor);

        /** @var User $want */
        $want = User::factory()->make();
        $wantRole = Roles::Admin;

        $action = app(UpdateUserAction::class);

        $user = $action(
            user: $user,
            email: $want->email,
            enabled: $want->enabled,
            role: $wantRole,
        );

        $this->assertDatabaseHas('users', [
            'username' => $user->username,
            'email' => $want->email,
            'enabled' => $want->enabled,
        ]);

        $this->assertEquals($wantRole->value, $user->role->value);
    }
}
