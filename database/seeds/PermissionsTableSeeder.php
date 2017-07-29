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

class PermissionsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permission_role')->delete();
        DB::table('permissions')->delete();

        $permissions = array(
            array(
                'name' => 'manage_users',
                'display_name' => 'Manage SSH Users',
            ),
            array(
                'name' => 'manage_hosts',
                'display_name' => 'Manage Hosts',
            ),
            array(
                'name' => 'manage_permissions',
                'display_name' => 'Manage Permissions',
            ),
            array(
                'name' => 'manage_admins',
                'display_name' => 'Manage Administrators',
            ),
            array(
                'name' => 'can_access',
                'display_name' => 'Can Access to UI'
            )
        );

        foreach ($permissions as $permissionData) {
            $permission = new Permission;
            $permission->name = $permissionData['name'];
            $permission->display_name = $permissionData['display_name'];
            $permission->save();
        }
    }

}
