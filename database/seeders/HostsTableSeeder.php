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

use App\Models\Host;
use Illuminate\Database\Seeder;

class HostsTableSeeder extends Seeder
{
    public function run(): void
    {
        Host::create([
            'hostname' => 'ssh-server',
            'username' => 'admin',
            'port' => 22,
            'authorized_keys_file' => '.ssh/authorized_keys',
        ]);

        Host::factory()
            ->count(3)
            ->create([
                'enabled' => false,
            ]);
    }
}
