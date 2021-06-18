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

use App\Enums\KeyOperation;
use App\Models\Key;
use App\Models\Keygroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;
use Tests\Traits\InteractsWithPermissions;

class KeyControllerTest extends TestCase
{
    use RefreshDatabase;
    use InteractsWithPermissions;

    const VALID_PUBLIC_KEY_ONE = 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAAAgQDl8cMHgSYgkMFo27dvnv+1RY3el3628wCF6h+fvNwH5YLbKQZTSSFlWH6BMsMahMp3zYOvb4kURkloaPTX6paZZ+axZo6Uhww+ISws3fkykEhZWanOABy1/cKjT36SqfJD/xFVgL+FaE5QB5gvarf2IH1lNT9iYutKY0hJVz15IQ== valid-key-one';
    const VALID_PUBLIC_KEY_TWO = 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAAAgQDdZTBjbqXy299z3erXD0/rumaLZwfS1IwFmsPex+oTwytekdeoCAPr86jU+pDFAtxTqhNU5HMo8ZKwdDw6csbHkh6SpV0R8O7u0w8oVs7MIhr4Lm2Uhyl/tF5BrzerhSMk5esKlVAjdYyyLxE/JsJqGaZbchrDCHu1trH9Oy5+yw== valid-key-two';

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->disablePermissionsCheck();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function index_method_should_return_proper_view(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('keys.index'));

        $response->assertSuccessful();
        $response->assertViewIs('key.index');
    }

    /** @test */
    public function create_method_should_return_proper_view(): void
    {
        $groups = Keygroup::factory()
            ->count(3)
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->get(route('keys.create'));

        $response->assertSuccessful();
        $response->assertViewIs('key.create');
        $response->assertViewHas('groups', $groups->pluck('name', 'id'));
    }

    /** @test */
    public function store_method_should_create_a_new_key_when_it_is_not_supplied(): void
    {
        $key = Key::factory()->make();

        $response = $this
            ->actingAs($this->user)
            ->post(route('keys.store'), [
                'username' => $key->username,
                'operation' => KeyOperation::CREATE_OPERATION,
            ]);

        $response->assertStatus(Response::HTTP_FOUND); // Can't use assertRedirect() because we don't know the created UUID.
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('keys', [
            'username' => $key->username,
        ]);
    }

    /** @test */
    public function store_method_should_fail_when_invalid_key_is_supplied(): void
    {
        $key = Key::factory()->make();

        $response = $this
            ->actingAs($this->user)
            ->post(route('keys.store'), [
                'username' => $key->username,
                'operation' => KeyOperation::IMPORT_OPERATION,
                'public_key' => 'Invalid Key',
            ]);

        $response->assertStatus(Response::HTTP_FOUND); // Can't use assertRedirect() because we don't know the created UUID.
        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing('keys', [
            'username' => $key->username,
        ]);
    }

    /** @test */
    public function store_method_should_create_a_key_when_valid_key_is_supplied(): void
    {
        $key = Key::factory()->make();

        $response = $this
            ->actingAs($this->user)
            ->post(route('keys.store'), [
                'username' => $key->username,
                'operation' => KeyOperation::IMPORT_OPERATION,
                'public_key' => self::VALID_PUBLIC_KEY_ONE,
            ]);

        $response->assertStatus(Response::HTTP_FOUND); // Can't use assertRedirect() because we don't know the created UUID.
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('keys', [
            'username' => $key->username,
            'public' => self::VALID_PUBLIC_KEY_ONE,
        ]);
    }

    /** @test */
    public function edit_method_should_return_proper_view(): void
    {
        $key = Key::factory()
            ->create();
        $groups = Keygroup::factory()
            ->count(3)
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->get(route('keys.edit', $key->id));

        $response->assertSuccessful();
        $response->assertViewIs('key.edit');
        $response->assertViewHas('key', $key);
        $response->assertViewHas('groups', $groups->pluck('name', 'id'));
    }

    /** @test */
    public function update_method_should_maintain_the_key_when_noop_operation_is_used(): void
    {
        $wantPublicKey = self::VALID_PUBLIC_KEY_ONE;
        $key = Key::factory()->create([
            'public' => $wantPublicKey,
        ]);

        $response = $this
            ->actingAs($this->user)
            ->put(route('keys.update', $key), [
                'operation' => KeyOperation::NOOP_OPERATION,
                'enabled' => true,
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('keys', ['id' => $key->id, 'public' => $wantPublicKey]);
    }

    /** @test */
    public function update_method_should_change_the_key_when_import_operation_is_used(): void
    {
        $key = Key::factory()->create([
            'public' => self::VALID_PUBLIC_KEY_ONE,
        ]);
        $wantPublicKey = self::VALID_PUBLIC_KEY_TWO;

        $response = $this
            ->actingAs($this->user)
            ->put(route('keys.update', $key), [
                'operation' => KeyOperation::IMPORT_OPERATION,
                'public_key' => $wantPublicKey,
                'enabled' => true,
            ]);

        $response->assertRedirect(route('keys.update', $key));
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('keys', [
            'id' => $key->id,
            'public' => $wantPublicKey,
        ]);
    }

    /** @test */
    public function update_method_should_create_a_new_key_when_create_operation_is_used(): void
    {
        $key = Key::factory()->create([
            'public' => self::VALID_PUBLIC_KEY_ONE,
        ]);

        $response = $this
            ->actingAs($this->user)
            ->put(route('keys.update', $key), [
                'operation' => KeyOperation::CREATE_OPERATION,
                'enabled' => true,
            ]);

        $response->assertRedirect(route('keys.update', $key));
        $response->assertSessionHasNoErrors();
        $this->assertNotEquals(self::VALID_PUBLIC_KEY_ONE, optional(Key::findOrFail($key->id))->public);
    }

    /** @test */
    public function destroy_method_should_return_proper_success_message(): void
    {
        $key = Key::factory()
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->delete(route('keys.destroy', $key));

        $response->assertRedirect(route('keys.index'));
        $response->assertSessionHas('success');
        $this->assertDeleted($key);
    }

    /** @test */
    public function data_method_should_return_error_when_not_ajax(): void
    {
        $response = $this
            ->actingAs($this->user)
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
            ->actingAs($this->user)
            ->ajaxGet(route('keys.data'));

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'username',
                    'fingerprint',
                    'groups',
                    'actions',
                ],
            ],
        ]);
        foreach ($keys as $key) {
            $response->assertJsonFragment([
                'username' => $key['username'],
                'fingerprint' => $key['fingerprint'],
            ]);
        }
    }
}
