<?php
/**
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2020 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 - 2020 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *
 * @link        https://github.com/pacoorozco/ssham
 */

namespace Database\Seeders;

use App\Models\Key;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KeysTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('keys')->delete();

        Key::factory()
            ->count(3)
            ->create();
    }
}
