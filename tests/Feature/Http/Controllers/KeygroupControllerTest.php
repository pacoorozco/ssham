<?php
/**
 * SSH Access Manager - SSH keygroups management solution.
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

use App\Key;
use App\Keygroup;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KeygroupControllerTest extends TestCase
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
            ->get(route('keygroups.index'));

        $response->assertSuccessful();
        $response->assertViewIs('keygroup.index');
    }

    public function test_create_method_returns_proper_view()
    {
        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('keygroups.create'));

        $response->assertSuccessful();
        $response->assertViewIs('keygroup.create');
    }

    public function test_create_method_returns_proper_data()
    {
        $keys = factory(Key::class, 3)->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('keygroups.create'));

        $response->assertSuccessful();
        $response->assertViewHas('keys', $keys->pluck('username', 'id'));
    }

    public function test_edit_method_returns_proper_view()
    {
        $group = factory(Keygroup::class)->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('keygroups.edit', $group->id));

        $response->assertSuccessful();
        $response->assertViewIs('keygroup.edit');
        $response->assertViewHas('keygroup', $group);
    }

    public function test_edit_method_returns_proper_data()
    {
        $group = factory(Keygroup::class)->create();
        $keys = factory(Key::class, 3)->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('keygroups.edit', $group->id));

        $response->assertSuccessful();
        $response->assertViewHas('keys', $keys->pluck('username', 'id'));
    }

    public function test_delete_method_returns_proper_view()
    {
        $group = factory(Keygroup::class)->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('keygroups.delete', $group->id));

        $response->assertSuccessful();
        $response->assertViewIs('keygroup.delete');
        $response->assertViewHas('keygroup', $group);
    }

    public function test_destroy_method_returns_proper_success_message()
    {
        $group = factory(Keygroup::class)->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->delete(route('keygroups.destroy', $group->id));

        $response->assertSessionHas('success');
    }

    public function test_data_method_returns_error_when_not_ajax()
    {
        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('keygroups.data'));

        $response->assertForbidden();
    }

    public function test_data_method_returns_data()
    {
        $groups = factory(Keygroup::class, 3)->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->ajaxGet(route('keygroups.data'));

        $response->assertSuccessful();
        foreach ($groups as $group) {
            $response->assertJsonFragment([
                'name' => $group->name,
                'description' => $group->description,
                'keys' => '0',
                'rules' => trans_choice('rule/model.items_count', 0, ['value' => 0]),
            ]);
        }
    }
}
