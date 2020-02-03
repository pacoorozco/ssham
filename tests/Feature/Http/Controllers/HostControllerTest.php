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

namespace Tests\Unit\Http\Controllers;

use App\Host;
use App\Hostgroup;
use App\keygroupgroup;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HostControllerTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    private $user_to_act_as;

    public function setUp(): void
    {
        parent::setUp();
        $this->user_to_act_as = factory(User::class)->create();
    }

    public function test_index_method_returns_proper_view()
    {
        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('hosts.index'));

        $response->assertSuccessful();
        $response->assertViewIs('host.index');
    }

    public function test_create_method_returns_proper_view()
    {
        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('hosts.create'));

        $response->assertSuccessful();
        $response->assertViewIs('host.create');
    }

    public function test_create_method_returns_proper_data()
    {
        $groups = factory(Hostgroup::class, 3)->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('hosts.create'));

        $response->assertSuccessful();
        $response->assertViewHas('groups', $groups->pluck('name', 'id'));
    }

    public function test_edit_method_returns_proper_view()
    {
        $host = factory(Host::class)->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('hosts.edit', $host->id));

        $response->assertSuccessful();
        $response->assertViewIs('host.edit');
        $response->assertViewHas('host', $host);
    }

    public function test_edit_method_returns_proper_data()
    {
        $host = factory(Host::class)->create();
        $groups = factory(Hostgroup::class, 3)->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('hosts.edit', $host->id));

        $response->assertSuccessful();
        $response->assertViewHas('groups', $groups->pluck('name', 'id'));
    }

    public function test_delete_method_returns_proper_view()
    {
        $host = factory(Host::class)->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('hosts.delete', $host->id));

        $response->assertSuccessful();
        $response->assertViewIs('host.delete');
        $response->assertViewHas('host', $host);
    }

    public function test_destroy_method_returns_proper_success_message()
    {
        $host = factory(Host::class)->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->delete(route('hosts.destroy', $host->id));

        $response->assertSessionHas('success');
    }

    public function test_data_method_returns_error_when_not_ajax()
    {
        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('hosts.data'));

        $response->assertForbidden();
    }

    public function test_data_method_returns_data()
    {
        $hosts = factory(Host::class, 3)->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->ajaxGet(route('hosts.data'));

        $response->assertSuccessful();
        foreach ($hosts as $host) {
            $response->assertJsonFragment([
                'hostname' => $host['hostname'],
                'username' => $host['username'],
                'type' => $host['type'],
                'groups' => '0',
            ]);
        }
    }
}
