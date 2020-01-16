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

use App\Hostgroup;
use App\Rule;
use App\Usergroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HostgroupUsergroupPermissionsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hostgroup_usergroup_permissions')->delete();

        $permissions = array(
            array(
                'usergroup' => 'developers',
                'hostgroup' => 'DEV_servers',
                'action' => 'allow',
                'description' => 'Developers can develop on development hosts',
            ),
            array(
                'usergroup' => 'operators',
                'hostgroup' => 'PRO_servers',
                'action' => 'deny',
                'description' => 'Operators can make its magic on production hosts',
            )
        );

        foreach ($permissions as $permission) {
            $usergroup = Usergroup::where('name', $permission['usergroup'])->firstOrFail();
            $hostgroup = Hostgroup::where('name', $permission['hostgroup'])->firstOrFail();

            Rule::create([
                'usergroup_id' => $usergroup->id,
                'hostgroup_id' => $hostgroup->id,
                'action' => $permission['action'],
                'name' => $permission['description']
            ]);
        }
    }

}
