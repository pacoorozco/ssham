<?php
/*
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2021 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2017 - 2021 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/ssham
 */

namespace Tests\Unit\Models;

use App\Models\User;
use Tests\ModelTestCase;

class UserTest extends ModelTestCase
{
    public function test_contains_valid_fillable_properties()
    {
        $m = new User();
        $this->assertEquals([
            'username',
            'email',
            'password',
            'enabled',
        ], $m->getFillable());
    }

    public function test_contains_valid_hidden_properties()
    {
        $m = new User();
        $this->assertEquals([
            'password',
            'remember_token',
            'auth_type',
        ], $m->getHidden());
    }

    public function test_contains_valid_casts_properties()
    {
        $m = new User();
        $this->assertEquals([
            'id' => 'int',
            'enabled' => 'boolean',
            'email_verified_at' => 'datetime',
        ], $m->getCasts());
    }

    public function test_username_is_lowercase()
    {
        $m = new User();

        $test_data = [
            'User' => 'user',
            'ADMIN' => 'admin',
            'user' => 'user',
            'admin' => 'admin',
        ];

        foreach ($test_data as $input => $want) {
            $m->username = $input;
            $this->assertEquals($want, $m->getAttribute('username'));
        }
    }
}
