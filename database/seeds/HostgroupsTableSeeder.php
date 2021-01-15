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

use App\Activity;
use App\Hostgroup;
use Illuminate\Database\Seeder;

class HostgroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hostgroups = [
            [
                'name' => 'PRO_servers',
                'description' => 'Production Servers',
            ],
            [
                'name' => 'DEV_servers',
                'description' => 'Development Servers',
            ],
        ];

        foreach ($hostgroups as $groupData) {
            $group = Hostgroup::create($groupData);
            activity()
                ->performedOn($group)
                ->withProperties(['status' => Activity::STATUS_SUCCESS])
                ->log(sprintf("Create host group '%s'.", $group->name));
        }
    }
}
