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

use SSHAM\User;

class UserUnitTest extends TestCase
{
    /**
     * Test User username is lower cased
     */
    public function testUsernameAttributeIsLowerCased()
    {
        $expectedUser = factory(User::class)->make();

        $user = new User();
        $user->username = strtoupper($expectedUser->username);

        // Attribute must be lower cased
        $this->assertEquals($expectedUser->username, $user->username);
    }
}
