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

use App\Key;
use App\Keygroup;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KeyControllerTest extends TestCase
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
            ->get(route('keys.index'));

        $response->assertSuccessful();
        $response->assertViewIs('key.index');
    }

    public function test_create_method_returns_proper_view()
    {
        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('keys.create'));

        $response->assertSuccessful();
        $response->assertViewIs('key.create');
    }

    public function test_create_method_returns_proper_data()
    {
        $groups = factory(Keygroup::class, 3)->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('keys.create'));

        $response->assertSuccessful();
        $response->assertViewHas('groups', $groups->pluck('name', 'id'));
    }

    public function test_edit_method_returns_proper_view()
    {
        $key = factory(Key::class)->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('keys.edit', $key->id));

        $response->assertSuccessful();
        $response->assertViewIs('key.edit');
        $response->assertViewHas('key', $key);
    }

    public function test_edit_method_returns_proper_data()
    {
        $key = factory(Key::class)->create();
        $groups = factory(Keygroup::class, 3)->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('keys.edit', $key->id));

        $response->assertSuccessful();
        $response->assertViewHas('groups', $groups->pluck('name', 'id'));
    }

    public function test_delete_method_returns_proper_view()
    {
        $key = factory(Key::class)->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('keys.delete', $key->id));

        $response->assertSuccessful();
        $response->assertViewIs('key.delete');
        $response->assertViewHas('key', $key);
    }

    public function test_destroy_method_returns_proper_success_message()
    {
        $key = factory(Key::class)->create();

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->delete(route('keys.destroy', $key->id));

        $response->assertSessionHas('success');
    }

    public function test_data_method_returns_error_when_not_ajax()
    {
        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('keys.data'));

        $response->assertForbidden();
    }

    public function test_data_method_returns_data()
    {
        $keys = factory(Key::class, 3)->create(['enabled' => 'true']);

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

    public function test_downloadPrivateKey_method_returns_downloadable_file()
    {
        $key = factory(Key::class)->create(['private' => 'blah blah blah']);

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->ajaxGet(route('keys.download', $key->id));

        $response->assertSuccessful();
        $response->assertHeader('Content-Type', 'application/pkcs8');
        $response->assertHeader('Content-Disposition', 'attachment; filename="' . $key->username . '.key"');
    }

    public function test_downloadPrivateKey_method_returns_error_when_private_key_is_not_present()
    {
        $key = factory(Key::class)->create(['private' => null]);

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->ajaxGet(route('keys.download', $key->id));

        $response->assertNotFound();
    }
}
