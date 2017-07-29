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
use SSHAM\Hostgroup;

class HostgroupsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hostgroups')->delete();

        $hostgroups = array(
            array(
                'name' => 'PRO_servers',
                'description' => 'Production Servers'
            ),
            array(
                'name' => 'DEV_servers',
                'description' => 'Developement Servers'
            )
        );

        foreach ($hostgroups as $group) {
            Hostgroup::create($group);
        }
    }

}
