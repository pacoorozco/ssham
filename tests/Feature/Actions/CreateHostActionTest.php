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
use App\Models\Host;
use App\Models\Hostgroup;
use Generator;
use Tests\Feature\TestCase;

class CreateHostActionTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider providesHostTestCases
     */
    public function it_can_create_a_host(
        array $want
    ): void {
        $action = app(CreateHostAction::class);

        $groups = Hostgroup::factory()
            ->count($want['groups'])
            ->create();

        $action(
            hostname: $want['hostname'],
            username: $want['username'],
            enabled: $want['enabled'],
            port: $want['port'],
            authorizedKeysFile: $want['authorized_keys_file'],
            groups: $groups->pluck('id')->toArray(),
        );

        $host = Host::query()
            ->where('hostname', $want['hostname'])
            ->where('username', $want['username'])
            ->where('enabled', $want['enabled'])
            ->where('port', $want['port'])
            ->where('authorized_keys_file', $want['authorized_keys_file'])
            ->first();

        $this->assertInstanceOf(Host::class, $host);

        $this->assertCount($want['groups'], $host->groups);
    }

    public static function providesHostTestCases(): Generator
    {
        yield 'custom values' => [
            'want' => [
                'hostname' => 'server.domain.local',
                'username' => 'john.doe',
                'enabled' => true,
                'port' => 2022,
                'authorized_keys_file' => 'custom_authorized_keys_file',
                'groups' => 2,
            ],
        ];

        yield 'null values' => [
            'want' => [
                'hostname' => 'server.domain.local',
                'username' => 'john.doe',
                'enabled' => true,
                'port' => null,
                'authorized_keys_file' => null,
                'groups' => 0,
            ],
        ];

        yield 'custom port' => [
            'want' => [
                'hostname' => 'server.domain.local',
                'username' => 'john.doe',
                'enabled' => true,
                'port' => 2022,
                'authorized_keys_file' => null,
                'groups' => 0,
            ],
        ];
    }
}
