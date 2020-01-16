<?php
/**
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2019 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 - 2019 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = array(
            array(
                'name' => 'admin',
                'description' => 'Administrative user',
                'abilities' => array(
                    'manage-users', 'manage-hosts', 'manage-permissions', 'manage-admins', 'login-ui'
                ),
            ),
            array(
                'name' => 'user',
                'description' => 'Regular user',
                'abilities' => array(),
            )
        );

        foreach ($roles as $roleData) {
            $role = Role::create([
                'name' => $roleData['name'],
                'description' => $roleData['description']
            ]);

            // Adds abilities
            $permissions = array();
            foreach ($roleData['abilities'] as $ability) {
                $permission = Permission::where('name', $ability)->firstOrFail();
                array_push($permissions, $permission);
            }
            $role->attachPermissions($permissions);
        }
    }
}
