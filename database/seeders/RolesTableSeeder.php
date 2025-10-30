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
            Roles::Auditor->value => [
                Permissions::ViewKeys->value,
                Permissions::ViewHosts->value,
                Permissions::ViewRules->value,
                Permissions::ViewUsers->value,
            ],
            Roles::Operator->value => [
                Permissions::ViewKeys->value,
                Permissions::ViewHosts->value,
                Permissions::ViewRules->value,
                Permissions::ViewUsers->value,
                Permissions::EditKeys->value,
                Permissions::EditHosts->value,
                Permissions::DeleteKeys->value,
                Permissions::DeleteHosts->value,
            ],
            Roles::Admin->value => [
                Permissions::ViewKeys->value,
                Permissions::ViewHosts->value,
                Permissions::ViewRules->value,
                Permissions::ViewUsers->value,
                Permissions::EditKeys->value,
                Permissions::EditHosts->value,
                Permissions::EditRules->value,
                Permissions::DeleteKeys->value,
                Permissions::DeleteHosts->value,
                Permissions::DeleteRules->value,
            ],
            Roles::SuperAdmin->value => [
                // gets all permissions.
                array_map(fn ($p) => $p->value, Permissions::cases()),
            ],
        ];

        foreach (Roles::cases() as $role) {
            /** @var Role $roleModel */
            $roleModel = Role::create(['name' => $role->value]);

            $this->giveManyPermissionsTo($roleModel, $permissionsToRole[$role->value]);
        }
    }

    private function giveManyPermissionsTo(Role $role, array $permissions): void
    {
        foreach ($permissions as $perm) {
            $role->givePermissionTo($perm);
        }
    }
}
