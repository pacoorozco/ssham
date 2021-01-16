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
use Illuminate\Support\Facades\Auth;

class HostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Host::class, 5)->create()->each(function (Host $host) {
            activity()
                ->performedOn($host)
                ->withProperties(['status' => Activity::STATUS_SUCCESS])
                ->log(sprintf("Create host '%s@%s'.", $host->username, $host->hostname));
        });
    }
}
