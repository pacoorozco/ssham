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

return [
    /*
    |--------------------------------------------------------------------------
    | User Model Language Lines
    |--------------------------------------------------------------------------
    |
    */

    'username'              => 'Username',
    'username_ph'           => 'john.doe',
    'name'                  => 'Name',
    'name_ph'               => 'John Doe',
    'email'                 => 'E-mail address',
    'email_ph'              => 'john@domain.com',
    'password'              => 'Password',
    'password_confirmation' => 'Password Confirmation',
    'public_key'            => 'SSH public key',
    'fingerprint'           => 'Key fingerprint',
    'active'                => 'Enabled',
    'roles'                 => 'Roles',
    'groups'                => 'Team memberships',
    'is_admin'              => 'Has this user administrative role?',

    'roles' => [
        'user'  => 'Normal user',
        'admin' => 'Administrator',
    ],
];
