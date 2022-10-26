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

use App\Actions\UpdateHostAction;
use App\Models\Host;
use Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateHostActionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @dataProvider provideNullableFieldsForHosts
     */
    public function it_can_update_a_host(
        array $nullable,
    ): void {
        $action = app(UpdateHostAction::class);

        /** @var Host $host */
        $host = Host::factory()
            ->customized()
            ->create();

        /** @var Host $want */
        $want = Host::factory()->make();

        $action(
            host: $host,
            enabled: $want->enabled,
            port: $nullable['port'] ?? $want->port,
            authorizedKeysFile: $nullable['authorized_keys_file'] ?? $want->authorized_keys_file,
            groups: [],
        );

        $this->assertDatabaseHas(Host::class, [
            'hostname'             => $host->hostname,
            'username'             => $host->username,
            'enabled'              => $want->enabled,
            'port'                 => $nullable['port'] ?? $want->port,
            'authorized_keys_file' => $nullable['authorized_keys_file'] ?? $want->authorized_keys_file,
        ]);
    }

    public function provideNullableFieldsForHosts(): Generator
    {
        yield 'without null values' => [
            'nullable' => [],
        ];

        yield 'null port' => [
            'nullable' => [
                'port' => null,
            ],
        ];

        yield 'null authorized_keys_file' => [
            'nullable' => [
                'authorized_keys_file' => null,
            ],
        ];
    }
}
