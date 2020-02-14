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

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

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
            ->get(route('users.index'));

        $response->assertSuccessful();
        $response->assertViewIs('user.index');
    }

    public function test_create_method_returns_proper_view()
    {
        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('users.create'));

        $response->assertSuccessful();
        $response->assertViewIs('user.create');
    }

    public function test_edit_method_returns_proper_view()
    {
        $user = factory(User::class)->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('users.edit', $user->id));

        $response->assertSuccessful();
        $response->assertViewIs('user.edit');
        $response->assertViewHas('user', $user);
    }

    public function test_delete_method_returns_proper_view()
    {
        $user = factory(User::class)->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('users.delete', $user->id));

        $response->assertSuccessful();
        $response->assertViewIs('user.delete');
        $response->assertViewHas('user', $user);
    }

    public function test_destroy_method_returns_proper_success_message()
    {
        $user = factory(User::class)->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->delete(route('users.destroy', $user->id));

        $response->assertSessionHas('success');
    }

    public function test_destroy_method_returns_error_message_when_deletes_myself()
    {
        $user = $this->user_to_act_as;

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->delete(route('users.destroy', $user->id));

        $response->assertSessionHas('errors');
    }

    public function test_data_method_returns_error_when_not_ajax()
    {
        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('users.data'));

        $response->assertForbidden();
    }

    public function test_data_method_returns_data()
    {
        $users = factory(User::class, 3)->create(['enabled' => 'true']);

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->ajaxGet(route('users.data'));

        $response->assertSuccessful();
        foreach ($users as $user) {
            $response->assertJsonFragment([
                'username' => $user->username,
                'email' => $user->email,
            ]);
        }
    }
}
