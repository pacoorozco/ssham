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

use App\Models\Key;
use App\Models\Keygroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KeyControllerTest extends TestCase
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
            ->get(route('keys.index'));

        $response->assertSuccessful();
        $response->assertViewIs('key.index');
    }

    /** @test */
    public function create_method_should_return_proper_view(): void
    {
        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('keys.create'));

        $response->assertSuccessful();
        $response->assertViewIs('key.create');
    }

    /** @test  */
    public function create_method_should_return_proper_data(): void
    {
        $groups = Keygroup::factory()
            ->count(3)
            ->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('keys.create'));

        $response->assertSuccessful();
        $response->assertViewHas('groups', $groups->pluck('name', 'id'));
    }

    /** @test */
    public function edit_method_should_return_proper_view(): void
    {
        $key = Key::factory()
            ->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('keys.edit', $key->id));

        $response->assertSuccessful();
        $response->assertViewIs('key.edit');
        $response->assertViewHas('key', $key);
    }

    /** @test  */
    public function edit_method_should_return_proper_data(): void
    {
        $key = Key::factory()
            ->create();

        $groups = Keygroup::factory()
            ->count(3)
            ->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('keys.edit', $key->id));

        $response->assertSuccessful();
        $response->assertViewHas('groups', $groups->pluck('name', 'id'));
    }

    /** @test */
    public function delete_method_should_return_proper_view(): void
    {
        $key = Key::factory()
            ->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('keys.delete', $key->id));

        $response->assertSuccessful();
        $response->assertViewIs('key.delete');
        $response->assertViewHas('key', $key);
    }

    /** @test */
    public function destroy_method_should_return_proper_success_message(): void
    {
        $key = Key::factory()
            ->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->delete(route('keys.destroy', $key->id));

        $response->assertSessionHas('success');
    }

    /** @test */
    public function data_method_should_return_error_when_not_ajax(): void
    {
        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('keys.data'));

        $response->assertForbidden();
    }

    /** @test */
    public function data_method_should_return_data(): void
    {
        $keys = $key = Key::factory()
            ->count(3)
            ->create(
                [
                    'enabled' => 'true',
                ]
            );

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->ajaxGet(route('keys.data'));

        $response->assertSuccessful();
        foreach ($keys as $key) {
            $response->assertJsonFragment([
                'username' => $key['username'],
                'fingerprint' => $key['fingerprint'],
                'groups' => '0',
            ]);
        }
    }
}
