<?php
/*
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2021 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2017 - 2021 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/ssham
 */

namespace Database\Seeders;

use App\Enums\Permissions;
use App\Enums\Roles;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        // Defines which Permissions are given to each Role.
        $permissionsToRole = [
            Roles::Auditor => [
                Permissions::ViewKeys,
                Permissions::ViewHosts,
                Permissions::ViewRules,
                Permissions::ViewUsers,
            ],
            Roles::Operator => [
                Permissions::ViewKeys,
                Permissions::ViewHosts,
                Permissions::ViewRules,
                Permissions::ViewUsers,
                Permissions::EditKeys,
                Permissions::EditHosts,
                Permissions::DeleteKeys,
                Permissions::DeleteHosts,
            ],
            Roles::Admin => [
                Permissions::ViewKeys,
                Permissions::ViewHosts,
                Permissions::ViewRules,
                Permissions::ViewUsers,
                Permissions::EditKeys,
                Permissions::EditHosts,
                Permissions::EditRules,
                Permissions::DeleteKeys,
                Permissions::DeleteHosts,
                Permissions::DeleteRules,
            ],
            Roles::SuperAdmin => [
                // gets all permissions.
                Permissions::getValues(),
            ],
        ];

        foreach (Roles::getValues() as $roleValue) {
            /** @var Role $role */
            $role = Role::create(['name' => $roleValue]);

            $this->giveManyPermissionsTo($role, $permissionsToRole[$roleValue]);
        }
    }

    private function giveManyPermissionsTo(Role $role, array $permissions): void
    {
        foreach ($permissions as $perm) {
            $role->givePermissionTo($perm);
        }
    }
}
