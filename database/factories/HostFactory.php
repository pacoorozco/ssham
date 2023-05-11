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

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Host>
 */
class HostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => fake()->userName,
            'hostname' => fake()->unique()->domainWord.'.'.fake()->domainName,
            'port' => 0,
            'enabled' => fake()->boolean,
        ];
    }

    /**
     * Indicates that the model should be disabled.
     */
    public function disabled(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'enabled' => false,
            ];
        });
    }

    /**
     * Indicates that the model should be enabled.
     */
    public function enabled(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'enabled' => true,
            ];
        });
    }

    /**
     * Indicates that the model should be in sync, wo/ pending changes.
     */
    public function withoutPendingChanges(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'synced' => true,
            ];
        });
    }

    /**
     * Indicates that the model should have pending changes.
     */
    public function withPendingChanges(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'synced' => false,
            ];
        });
    }

    /**
     * Indicates that the model have customized values.
     */
    public function customized(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'port' => fake()->numberBetween(1024, 65535),
                'authorized_keys_file' => '~/.ssh/authorized_keys',
                'enabled' => true,
            ];
        });
    }
}
