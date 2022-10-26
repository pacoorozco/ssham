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

use App\Enums\AuthType;
use App\Models\User;
use Tests\ModelTestCase;

class UserTest extends ModelTestCase
{
    /** @test */
    public function contains_valid_fillable_properties(): void
    {
        $m = new User();
        $this->assertEquals([
            'username',
            'email',
            'password',
            'enabled',
        ], $m->getFillable());
    }

    /** @test */
    public function contains_valid_hidden_properties(): void
    {
        $m = new User();
        $this->assertEquals([
            'password',
            'remember_token',
        ], $m->getHidden());
    }

    /** @test */
    public function contains_valid_casts_properties(): void
    {
        $m = new User();
        $this->assertEquals([
            'id'                => 'int',
            'enabled'           => 'boolean',
            'email_verified_at' => 'datetime',
            'auth_type'         => AuthType::class,
        ], $m->getCasts());
    }

    /** @test */
    public function username_is_lowercase(): void
    {
        $testCases = [
            'User'  => 'user',
            'ADMIN' => 'admin',
            'user'  => 'user',
            'admin' => 'admin',
        ];

        foreach ($testCases as $input => $want) {
            /** @var User $user */
            $user = User::factory()->makeOne([
                'username' => $input,
            ]);
            $this->assertEquals($want, $user->username);
        }
    }
}
