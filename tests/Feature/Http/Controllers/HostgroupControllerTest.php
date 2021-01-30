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

namespace Tests\Feature\Http\Controllers;

use App\Models\Host;
use App\Models\Hostgroup;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HostgroupControllerTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    private $user_to_act_as;

    public function setUp(): void
    {
        parent::setUp();
        $this->user_to_act_as = User::factory()
            ->create();
    }

    public function test_index_method_returns_proper_view()
    {
        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('hostgroups.index'));

        $response->assertSuccessful();
        $response->assertViewIs('hostgroup.index');
    }

    public function test_create_method_returns_proper_view()
    {
        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('hostgroups.create'));

        $response->assertSuccessful();
        $response->assertViewIs('hostgroup.create');
    }

    public function test_create_method_returns_proper_data()
    {
        $hosts = Host::factory()
            ->count(3)
            ->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('hostgroups.create'));

        $response->assertSuccessful();
        $response->assertViewHas('hosts', $hosts->pluck('hostname', 'id'));
    }

    public function test_edit_method_returns_proper_view()
    {
        $group = Hostgroup::factory()
            ->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('hostgroups.edit', $group->id));

        $response->assertSuccessful();
        $response->assertViewIs('hostgroup.edit');
        $response->assertViewHas('hostgroup', $group);
    }

    public function test_edit_method_returns_proper_data()
    {
        $group = Hostgroup::factory()
            ->create();
        $hosts = Host::factory()
            ->count(3)
            ->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('hostgroups.edit', $group->id));

        $response->assertSuccessful();
        $response->assertViewHas('hosts', $hosts->pluck('hostname', 'id'));
    }

    public function test_delete_method_returns_proper_view()
    {
        $group = Hostgroup::factory()
            ->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('hostgroups.delete', $group->id));

        $response->assertSuccessful();
        $response->assertViewIs('hostgroup.delete');
        $response->assertViewHas('hostgroup', $group);
    }

    public function test_destroy_method_returns_proper_success_message()
    {
        $group = Hostgroup::factory()
            ->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->delete(route('hostgroups.destroy', $group->id));

        $response->assertSessionHas('success');
    }

    public function test_data_method_returns_error_when_not_ajax()
    {
        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('hostgroups.data'));

        $response->assertForbidden();
    }

    public function test_data_method_returns_data()
    {
        $groups = Hostgroup::factory()
            ->count(3)
            ->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->ajaxGet(route('hostgroups.data'));

        $response->assertSuccessful();
        foreach ($groups as $group) {
            $response->assertJsonFragment([
                'name' => $group['name'],
                'description' => $group['description'],
                'hosts' => '0',
                'rules' => trans_choice('rule/model.items_count', 0, ['value' => 0]),
            ]);
        }
    }
}
