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
 * @link        https://github.com/pacoorozco/ssham
 */

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Key;
use App\Libs\RsaSshKey\RsaSshKey;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Key::class, function (Faker $faker) {
    $rsa = RsaSshKey::create();

    return [
        'username' => $faker->unique()->userName,
        'public' => RsaSshKey::getPublicKey($rsa['publickey']),
        'private' => null,
        'fingerprint' => RsaSshKey::getPublicFingerprint($rsa['publickey']),
        'enabled' => 'true',
    ];
});
