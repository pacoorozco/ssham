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
use Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateHostActionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @dataProvider providesHostTestCases
     */
    public function it_can_create_a_host(
        string $hostname,
        string $username,
        array $options,
        array $want,
    ): void {
        $action = app(CreateHostAction::class);

        $action(
            hostname: $hostname,
            username: $username,
            options: $options,
        );

        $this->assertDatabaseHas('hosts', $want);
    }

    public function providesHostTestCases(): Generator
    {
        yield 'custom options' => [
            'hostname' => 'server.domain.local',
            'username' => 'john.doe',
            'options' => [
                'enabled' => true,
                'port' => 2022,
                'authorized_keys_file' => 'custom_authorized_keys_file',
            ],
            'want' => [
                'hostname' => 'server.domain.local',
                'username' => 'john.doe',
                'enabled' => true,
                'port' => 2022,
                'authorized_keys_file' => 'custom_authorized_keys_file',
            ],
        ];

        yield 'empty options' => [
            'hostname' => 'server.domain.local',
            'username' => 'john.doe',
            'options' => [],
            'want' => [
                'hostname' => 'server.domain.local',
                'username' => 'john.doe',
                'enabled' => true,
                'port' => null,
                'authorized_keys_file' => null,
            ],
        ];

        yield 'only port option' => [
            'hostname' => 'server.domain.local',
            'username' => 'john.doe',
            'options' => [
                'port' => 2022,
            ],
            'want' => [
                'hostname' => 'server.domain.local',
                'username' => 'john.doe',
                'enabled' => true,
                'port' => 2022,
                'authorized_keys_file' => null,
            ],
        ];
    }
}
