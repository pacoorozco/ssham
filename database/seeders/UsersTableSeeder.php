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

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        $users = [
            [
                'username' => 'admin',
                'email' => 'admin@example.org',
                'auth_type' => 'local',
                'password' => bcrypt('secret'),
                'enabled' => true,
            ],
            [
                'username' => 'user',
                'email' => 'user@example.org',
                'auth_type' => 'local',
                'password' => bcrypt('user'),
                'enabled' => false,
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);
            activity()
                ->performedOn($user)
                ->withProperties(['status' => Activity::STATUS_SUCCESS])
                ->log(sprintf("Create user '%s'.", $user->username));
        }
    }
}
