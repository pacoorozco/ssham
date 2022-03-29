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
use App\Actions\UpdateHostAction;
use App\Models\Host;
use App\Models\Hostgroup;
use Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateHostActionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @dataProvider providesHostData
     */
    public function it_can_update_a_host(
        array $options,
    ): void {
        /** @var Host $host */
        $host = Host::factory()->create();

        $action = app(UpdateHostAction::class);

        $action(
            host: $host,
            options: $options,
        );

        $this->assertDatabaseHas(Host::class, [
            'hostname' => $host->hostname,
            'username' => $host->username,
            'enabled' => $options['enabled'] ?? $host->enabled,
            'port' => $options['port'] ?? $host->port,
            'authorized_keys_file' => $options['authorized_keys_file'] ?? $host->authorized_keys_file,
        ]);
    }

    public function providesHostData(): Generator
    {
        yield 'empty options' => [
            'options' => [],
        ];

        yield 'custom options' => [
            'options' => [
                'enabled' => true,
                'port' => 2022,
                'authorized_keys_file' => 'custom_authorized_keys_file',
            ],
        ];
    }
}
