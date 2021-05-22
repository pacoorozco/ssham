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

namespace Database\Factories;

use App\Libs\RsaSshKey\RsaSshKey;
use App\Models\Key;
use Illuminate\Database\Eloquent\Factories\Factory;

class KeyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Key::class;

    public function definition(): array
    {
        $rsa = RsaSshKey::create();

        return [
            'username' => $this->faker->unique()->userName,
            'public' => RsaSshKey::getPublicKey($rsa['publickey']),
            'private' => RsaSshKey::getPrivateKey($rsa['privatekey']),
            'enabled' => true,
        ];
    }
}
