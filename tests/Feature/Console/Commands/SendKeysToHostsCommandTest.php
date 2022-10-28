<?php
/*
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2022 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2017 - 2022 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/ssham
 */

namespace Tests\Feature\Console\Commands;

use App\Jobs\UpdateServer;
use App\Models\Host;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SendKeysToHostsCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_finnish_successfully_when_there_are_not_enabled_servers()
    {
        setting()->set('ssh_timeout', 5);

        Host::factory()
            ->disabled()
            ->create();

        Queue::fake();

        $this->artisan('ssham:send')
            ->expectsOutput('There are not pending servers.')
            ->assertSuccessful();

        Queue::assertNothingPushed();
    }

    /** @test */
    public function it_finnish_successfully_when_there_are_enabled_servers()
    {
        setting()->set('ssh_timeout', 5);

        $hosts = Host::factory()
            ->count(2)
            ->enabled()
            ->create();

        Host::factory()
            ->disabled()
            ->create();

        Queue::fake();

        $this->artisan('ssham:send')
            ->expectsOutput('Hosts to be updated: '.count($hosts))
            ->assertSuccessful();

        Queue::assertPushed(UpdateServer::class, count($hosts));
    }

    /** @test */
    public function it_should_only_update_hosts_with_pending_changes()
    {
        setting()->set('ssh_timeout', 5);

        $host = Host::factory()
            ->enabled()
            ->withPendingChanges()
            ->create();

        Host::factory()
            ->enabled()
            ->withoutPendingChanges()
            ->create();

        Queue::fake();

        $this->artisan('ssham:send --pending')
            ->expectsOutput('Hosts to be updated: 1')
            ->expectsOutput("-> Queueing job for {$host->full_hostname}...")
            ->assertSuccessful();

        Queue::assertPushed(UpdateServer::class, 1);
    }
}
