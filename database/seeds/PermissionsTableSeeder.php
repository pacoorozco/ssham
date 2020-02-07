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
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = array(
            array(
                'name' => 'manage-keys',
                'display_name' => 'Manage SSH Keys',
            ),
            array(
                'name' => 'manage-hosts',
                'display_name' => 'Manage Hosts',
            ),
            array(
                'name' => 'manage-groups',
                'display_name' => 'Manage Groups',
            ),
            array(
                'name' => 'manage-control-rules',
                'display_name' => 'Manage Control Rules',
            ),
            array(
                'name' => 'manage-admins',
                'display_name' => 'Manage Administrators',
            )
        );

        foreach ($permissions as $permissionData) {
            Permission::create($permissionData);
        }
    }
}
