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

namespace Tests\Feature\Actions;

use App\Actions\CreateHostAction;
use App\Models\Hostgroup;
use Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateHostActionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @dataProvider providesHostData
     */
    public function it_can_create_a_host(
        string $hostname,
        string $username,
        array $options,
    ): void {
        $action = app(CreateHostAction::class);

        $action(
            hostname: $hostname,
            username: $username,
            options: $options,
        );

        $this->assertDatabaseHas('hosts', [
            'hostname' => $hostname,
            'username' => $username,
            'enabled' => $options['enabled'] ?? true,
            'port' => $options['port'] ?? 0,
            'authorized_keys_file' => $options['authorized_keys_file'] ?? '',
        ]);
    }

    public function providesHostData(): Generator
    {
        yield 'custom options' => [
            'hostname' => 'server.domain.local',
            'username' => 'john.doe',
            'options' => [
                'enabled' => true,
                'port' => 2022,
                'authorized_keys_file' => 'custom_authorized_keys_file',
            ],
        ];

        yield 'empty options' => [
            'hostname' => 'server.domain.local',
            'username' => 'john.doe',
            'options' => [],
        ];

    }
}
