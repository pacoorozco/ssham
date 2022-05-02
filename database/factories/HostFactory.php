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

use App\Models\Host;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Host>
 */
class HostFactory extends Factory
{
    protected $model = Host::class;

    public function definition(): array
    {
        return [
            'username' => $this->faker->userName,
            'hostname' => $this->faker->unique()->domainWord . '.' . $this->faker->domainName,
            'port' => 0,
            'enabled' => $this->faker->boolean,
        ];
    }

    public function disabled(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'enabled' => false,
            ];
        });
    }

    public function enabled(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'enabled' => true,
            ];
        });
    }

    public function synced(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'synced' => true,
            ];
        });
    }

    public function desynced(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'synced' => false,
            ];
        });
    }

    public function customized(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'port' => $this->faker->numberBetween(1024, 65535),
                'authorized_keys_file' => '~/.ssh/authorized_keys',
                'enabled' => true,
            ];
        });
    }
}
