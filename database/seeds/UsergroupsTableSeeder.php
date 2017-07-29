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
use SSHAM\Usergroup;
      
class UsergroupsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('usergroups')->delete();

        $usergroups = array(
            array(
                'name' => 'developers',
                'description' => 'Group of awesome developers'
            ),
            array(
                'name' => 'operators',
                'description' => 'Group of incredible operators'
            )
        );

        foreach ($usergroups as $group) {
            Usergroup::create($group);
        }
    }

}
