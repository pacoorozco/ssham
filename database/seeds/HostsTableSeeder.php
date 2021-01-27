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
        $host = Host::create([
            'hostname' => 'ssh-server',
            'username' => 'admin',
            'port' => 22,
            'authorized_keys_file' => '.ssh/authorized_keys',
        ]);
        activity()
            ->performedOn($host)
            ->withProperties(['status' => Activity::STATUS_SUCCESS])
            ->log(sprintf("Create host '%s@%s'.", $host->username, $host->hostname));

        factory(App\Host::class, 3)->create([
            'enabled' => false,
        ])->each(function (Host $host) {
            activity()
                ->performedOn($host)
                ->withProperties(['status' => Activity::STATUS_SUCCESS])
                ->log(sprintf("Create host '%s@%s'.", $host->username, $host->hostname));
        });
    }
}
