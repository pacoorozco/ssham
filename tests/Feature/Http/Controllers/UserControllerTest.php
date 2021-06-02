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

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user_to_act_as;

    public function setUp(): void
    {
        parent::setUp();
        $this->user_to_act_as = User::factory()
            ->create();
    }

    /** @test */
    public function index_method_should_return_proper_view(): void
    {
        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('users.index'));

        $response->assertSuccessful();
        $response->assertViewIs('user.index');
    }

    /** @test */
    public function create_method_should_return_proper_view(): void
    {
        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('users.create'));

        $response->assertSuccessful();
        $response->assertViewIs('user.create');
    }

    /** @test */
    public function store_method_should_create_a_new_user(): void
    {
        $user = User::factory()->make();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->post(route('users.store'), [
                'username' => $user->username,
                'email' => $user->email,
                'password' => 'secret123',
                'password_confirmation' => 'secret123',
            ]);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', [
            'username' => $user->username,
            'email' => $user->email,
        ]);
    }

    /** @test */
    public function edit_method_should_return_proper_view(): void
    {
        $user = User::factory()
            ->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('users.edit', $user->id));

        $response->assertSuccessful();
        $response->assertViewIs('user.edit');
        $response->assertViewHas('user', $user);
    }

    /** @test */
    public function destroy_method_should_return_success_and_delete_user(): void
    {
        $user = User::factory()
            ->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->delete(route('users.destroy', $user));

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');
        $this->assertDeleted($user);
    }

    /** @test */
    public function destroy_method_should_return_error_message_when_deletes_myself(): void
    {
        $user = $this->user_to_act_as;

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->delete(route('users.destroy', $user));

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHas('errors');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
        ]);
    }

    /** @test */
    public function data_method_should_return_error_when_not_ajax(): void
    {
        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('users.data'));

        $response->assertForbidden();
    }

    /** @test */
    public function data_method_should_return_data(): void
    {
        $users = User::factory()
            ->count(3)
            ->create([
                'enabled' => 'true',
            ]);

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->ajaxGet(route('users.data'));

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'username',
                    'email',
                ],
            ],
        ]);
        foreach ($users as $user) {
            $response->assertJsonFragment([
                'username' => $user->username,
                'email' => $user->email,
            ]);
        }
    }
}
