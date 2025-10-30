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
 *
 * @link        https://github.com/pacoorozco/ssham
 */

namespace Database\Seeders;

use App\Models\ControlRule;
use App\Models\Hostgroup;
use App\Models\Keygroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HostgroupKeygroupPermissionsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('hostgroup_keygroup_permissions')->delete();

        $permissions = [
            [
                'keygroup' => 'developers',
                'hostgroup' => 'DEV_servers',
                'description' => 'Developers can develop on development hosts',
            ],
            [
                'keygroup' => 'operators',
                'hostgroup' => 'PRO_servers',
                'description' => 'Operators can make its magic on production hosts',
            ],
        ];

        foreach ($permissions as $permission) {
            $keygroup = Keygroup::where('name', $permission['keygroup'])->firstOrFail();
            $hostgroup = Hostgroup::where('name', $permission['hostgroup'])->firstOrFail();

            ControlRule::create([
                'source_id' => $keygroup->id,
                'target_id' => $hostgroup->id,
                'name' => $permission['description'],
            ]);
        }
    }
}
