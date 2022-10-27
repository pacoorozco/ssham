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

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->delete();

        $users = [
            [
                'username' => 'superadmin',
                'email' => 'superadmin@domain.local',
                'password' => bcrypt('superadmin'),
                'role' => Roles::SuperAdmin,
            ],
            [
                'username' => 'admin',
                'email' => 'admin@domain.local',
                'password' => bcrypt('admin'),
                'role' => Roles::Admin,
            ],
            [
                'username' => 'operator',
                'email' => 'operator@domain.local',
                'password' => bcrypt('operator'),
                'role' => Roles::Operator,
            ],
            [
                'username' => 'auditor',
                'email' => 'auditor@domain.local',
                'password' => bcrypt('auditor'),
                'role' => Roles::Auditor,
            ],
        ];

        foreach ($users as $userData) {
            /** @var \App\Models\User $user */
            $user = User::factory()->create(Arr::except($userData, 'role'));

            $user->assignRole($userData['role']);
        }
    }
}
