<?php
/**
 * SSHAM - SSH Access Manager Web Interface.
 *
 * Copyright (c) 2017 by Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of some open source application.
 *
 * Licensed under GNU General Public License 3.0.
 * Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

use Illuminate\Database\Seeder;
use SSHAM\Permission;
use SSHAM\Role;

class RolesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->delete();

        $roles = array(
            array(
                'name' => 'admin',
                'description' => 'Administrative user',
                'abilities' => array(
                    'manage_users', 'manage_hosts', 'manage_permissions', 'manage_admins', 'can_access'
                ),
            ),
            array(
                'name' => 'user',
                'description' => 'Regular user',
                'abilities' => array(),
            )
        );

        foreach ($roles as $roleData) {
            $role = new Role;
            $role->name = $roleData['name'];
            $role->description = $roleData['description'];
            $role->save();

            // Adds accesses
            foreach ($roleData['abilities'] as $ability) {
                $permission = Permission::where('name', $ability)->get()->first();
                $role->attachPermission($permission);
            }
        }
    }

}
