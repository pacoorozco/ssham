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

namespace Database\Factories;

use App\Models\Key;
use Illuminate\Database\Eloquent\Factories\Factory;
use PacoOrozco\OpenSSH\PrivateKey;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Key>
 */
class KeyFactory extends Factory
{
    protected $model = Key::class;

    public function definition(): array
    {
        $privateKey = PrivateKey::generate();
        $publicKey = $privateKey->getPublicKey();

        return [
            'username' => $this->faker->unique()->userName,
            'public' => (string) $publicKey,
            'private' => (string) $privateKey,
            'enabled' => true,
        ];
    }
}
