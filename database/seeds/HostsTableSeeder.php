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

use App\Host;
use Illuminate\Database\Seeder;

class HostsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hosts = array(
            array(
                'hostname' => 'server_1',
                'username' => 'root',
                'type' => 'linux',
            ),
            array(
                'hostname' => 'server_2',
                'username' => 'root',
                'type' => 'linux',
            ),
            array(
                'hostname' => 'server_3',
                'username' => 'root',
                'type' => 'linux',
            ),
        );

        foreach ($hosts as $host) {
            Host::create($host);
        }
    }

}
