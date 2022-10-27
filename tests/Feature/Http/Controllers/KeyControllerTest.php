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

namespace Tests\Feature\Http\Controllers;

use App\Enums\KeyOperation;
use App\Enums\Permissions;
use App\Models\Key;
use App\Models\Keygroup;
use App\Models\User;
use Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\InteractsWithPermissions;

class KeyControllerTest extends TestCase
{
    use RefreshDatabase;
    use InteractsWithPermissions;

    const VALID_RSA_PUBLIC_KEY_ONE = 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAAAgQDl8cMHgSYgkMFo27dvnv+1RY3el3628wCF6h+fvNwH5YLbKQZTSSFlWH6BMsMahMp3zYOvb4kURkloaPTX6paZZ+axZo6Uhww+ISws3fkykEhZWanOABy1/cKjT36SqfJD/xFVgL+FaE5QB5gvarf2IH1lNT9iYutKY0hJVz15IQ== phpseclib-generated-key';

    const VALID_RSA_PUBLIC_KEY_TWO = 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAAAgQDdZTBjbqXy299z3erXD0/rumaLZwfS1IwFmsPex+oTwytekdeoCAPr86jU+pDFAtxTqhNU5HMo8ZKwdDw6csbHkh6SpV0R8O7u0w8oVs7MIhr4Lm2Uhyl/tF5BrzerhSMk5esKlVAjdYyyLxE/JsJqGaZbchrDCHu1trH9Oy5+yw== phpseclib-generated-key';

    const VALID_ED25519_PUBLIC_KEY = 'ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIJWuYOcBGX/sfsSLBweKaIQAkzhnw3rqLiPddoqxj74z phpseclib-generated-key';

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->setupRolesAndPermissions();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function users_should_not_see_the_index_view(): void
    {
        $this
            ->actingAs($this->user)
            ->get(route('keys.index'))
            ->assertForbidden();
    }

    /** @test */
    public function viewers_should_see_the_index_view(): void
    {
        $this->user->givePermissionTo(Permissions::ViewKeys);

        $this
            ->actingAs($this->user)
            ->get(route('keys.index'))
            ->assertSuccessful()
            ->assertViewIs('key.index');
    }

    /** @test */
    public function users_should_not_see_any_key(): void
    {
        $key = Key::factory()->create();

        $this
            ->actingAs($this->user)
            ->get(route('keys.show', $key))
            ->assertForbidden();
    }

    /** @test */
    public function viewers_should_see_any_key(): void
    {
        $this->user->givePermissionTo(Permissions::ViewKeys);

        $key = Key::factory()->create();

        $this
            ->actingAs($this->user)
            ->get(route('keys.show', $key))
            ->assertSuccessful()
            ->assertViewIs('key.show')
            ->assertViewHas('key', $key);
    }

    /** @test */
    public function users_should_not_see_the_new_key_form(): void
    {
        Key::factory()->create();

        $this
            ->actingAs($this->user)
            ->get(route('keys.create'))
            ->assertForbidden();
    }

    /** @test */
    public function editors_should_see_the_new_key_form(): void
    {
        $this->user->givePermissionTo(Permissions::EditKeys);

        $keys = Keygroup::factory()
            ->count(2)
            ->create();

        $this
            ->actingAs($this->user)
            ->get(route('keys.create'))
            ->assertSuccessful()
            ->assertViewIs('key.create')
            ->assertViewHas('groups', $keys->pluck('name', 'id'));
    }

    /** @test */
    public function users_should_not_create_keys(): void
    {
        /** @var Key $want */
        $want = Key::factory()->make();

        $this
            ->actingAs($this->user)
            ->post(route('keys.store'), [
                'username' => $want->username,
                'operation' => KeyOperation::CREATE_OPERATION,
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing(Key::class, [
            'username' => $want->username,
        ]);
    }

    /**
     * @test
     * @dataProvider provideDataForKeyCreation
     */
    public function editors_should_create_keys(
        array $data,
    ): void {
        $this->user->givePermissionTo(Permissions::EditKeys);

        // Create some keys groups to test the membership.
        $groups = Keygroup::factory()
            ->count(2)
            ->create();

        /** @var Key $want */
        $want = Key::factory()->make();

        $formData = array_merge([
            'username' => $want->username,
            'operation' => KeyOperation::CREATE_OPERATION,
            'groups' => $groups->pluck('id')->toArray(),
        ], $data);

        $this
            ->actingAs($this->user)
            ->post(route('keys.store'), $formData)
            ->assertValid();

        $key = Key::query()
            ->where('username', $want->username)
            ->first();

        $this->assertInstanceOf(Key::class, $key);

        $this->assertCount(count($groups), $key->groups);

        if (isset($formData['public_key'])) {
            $this->assertEquals($formData['public_key'], $key->public);
        }
    }

    public function provideDataForKeyCreation(): Generator
    {
        yield 'creating a key' => [
            'data' => [
                'operation' => KeyOperation::CREATE_OPERATION,
            ],
        ];

        yield 'importing a RSA key' => [
            'data' => [
                'operation' => KeyOperation::IMPORT_OPERATION,
                'public_key' => self::VALID_RSA_PUBLIC_KEY_ONE,
            ],
        ];

        yield 'importing a ED25519 key' => [
            'data' => [
                'operation' => KeyOperation::IMPORT_OPERATION,
                'public_key' => self::VALID_ED25519_PUBLIC_KEY,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provideWrongDataForKeyCreation
     */
    public function editors_should_get_errors_when_creating_keys_with_wrong_data(
        array $data,
        array $errors
    ): void {
        $this->user->givePermissionTo(Permissions::EditKeys);

        // Key to validate unique rules...
        Key::factory()->create([
            'username' => 'foo',
        ]);

        /** @var Key $want */
        $want = Key::factory()->make();

        $formData = [
            'username' => $data['username'] ?? $want->username,
            'operation' => $data['operation'] ?? KeyOperation::CREATE_OPERATION,
        ];

        $this
            ->actingAs($this->user)
            ->post(route('keys.store'), $formData)
            ->assertInvalid($errors);

        if ($formData['username'] != 'foo') {
            $this->assertDatabaseMissing(Key::class, [
                'username' => $formData['username'],
            ]);
        }
    }

    public function provideWrongDataForKeyCreation(): Generator
    {
        yield 'username is empty' => [
            'data' => [
                'username' => '',
            ],
            'errors' => ['username'],
        ];

        yield 'username > 255 chars' => [
            'data' => [
                'username' => '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345',
            ],
            'errors' => ['username'],
        ];

        yield 'username ! valid' => [
            'data' => [
                'username' => '_foo34',
            ],
            'errors' => ['username'],
        ];

        yield 'username is taken' => [
            'data' => [
                'username' => 'foo',
            ],
            'errors' => ['username'],
        ];

        yield 'operation ! valid' => [
            'data' => [
                'operation' => 'not-valid-operation',
            ],
            'errors' => ['operation'],
        ];

        yield 'public_key ! valid' => [
            'data' => [
                'operation' => KeyOperation::IMPORT_OPERATION,
                'public_key' => 'this-public-key-is-invalid',
            ],
            'errors' => ['public_key'],
        ];
    }

    /** @test */
    public function users_should_not_see_edit_key_form(): void
    {
        $key = Key::factory()->create();

        // Create some groups to test the membership.
        Keygroup::factory()->create();

        $this
            ->actingAs($this->user)
            ->get(route('keys.edit', $key))
            ->assertForbidden();
    }

    /** @test */
    public function editors_should_see_the_edit_key_form(): void
    {
        $this->user->givePermissionTo(Permissions::EditKeys);

        $key = Key::factory()->create();

        // Create some keys to test the membership.
        $groups = Keygroup::factory()
            ->count(2)
            ->create();

        $this
            ->actingAs($this->user)
            ->get(route('keys.edit', $key))
            ->assertSuccessful()
            ->assertViewIs('key.edit')
            ->assertViewHas('key', $key)
            ->assertViewHas('groups', $groups->pluck('name', 'id'));
    }

    /**
     * @test
     * @dataProvider provideDataForKeyModification
     */
    public function editors_should_update_keys(
        array $data,
    ): void {
        $this->user->givePermissionTo(Permissions::EditKeys);

        // Create some groups to test the membership.
        $groups = Keygroup::factory()
            ->count(2)
            ->create();

        /** @var Key $key */
        $key = Key::factory()->create();

        /** @var Key $want */
        $want = Key::factory()->make();

        $formData = array_merge([
            'enabled' => $want->enabled,
            'operation' => KeyOperation::NOOP_OPERATION,
            'groups' => $groups->pluck('id')->toArray(),
        ], $data);

        $this
            ->actingAs($this->user)
            ->put(route('keys.update', $key), $formData)
            ->assertRedirect(route('keys.show', ['key' => $key]))
            ->assertValid();

        $key->refresh();

        $this->assertModelExists($key);

        $this->assertCount(count($groups), $key->groups);
    }

    public function provideDataForKeyModification(): Generator
    {
        yield 'creating a key' => [
            'data' => [
                'operation' => KeyOperation::CREATE_OPERATION,
            ],
        ];

        yield 'importing a RSA key' => [
            'data' => [
                'operation' => KeyOperation::IMPORT_OPERATION,
                'public_key' => self::VALID_RSA_PUBLIC_KEY_TWO,
            ],
        ];

        yield 'importing a ED25519 key' => [
            'data' => [
                'operation' => KeyOperation::IMPORT_OPERATION,
                'public_key' => self::VALID_ED25519_PUBLIC_KEY,
            ],
        ];

        yield 'not touching the key' => [
            'data' => [
                'operation' => KeyOperation::NOOP_OPERATION,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provideWrongDataForKeyModification
     */
    public function editors_should_get_errors_when_updating_keys_with_wrong_data(
        array $data,
        array $errors
    ): void {
        $this->user->givePermissionTo(Permissions::EditKeys);

        /** @var Key $key */
        $key = Key::factory()->create();

        /** @var Key $want */
        $want = Key::factory()->make();

        $formData = [
            'enabled' => $data['enabled'] ?? $want->enabled,
            'operation' => $data['operation'] ?? KeyOperation::NOOP_OPERATION,
            'public_key' => $data['public_key'] ?? $key->public,
            'groups' => $data['groups'] ?? [],
        ];

        $this
            ->actingAs($this->user)
            ->put(route('keys.update', $key), $formData)
            ->assertInvalid($errors);

        $this->assertDatabaseMissing(Key::class, [
            'id' => $key->id,
            'username' => $key->username,
            'enabled' => $formData['enabled'],
            'public' => $formData['public_key'],
        ]);

        $key->refresh();

        $this->assertCount(0, $key->groups);
    }

    public function provideWrongDataForKeyModification(): Generator
    {
        yield 'enabled ! valid' => [
            'data' => [
                'enabled' => 'non-boolean',
            ],
            'errors' => ['enabled'],
        ];

        yield 'public_key ! valid' => [
            'data' => [
                'operation' => KeyOperation::IMPORT_OPERATION,
                'public_key' => 'this-public-key-is-invalid',
            ],
            'errors' => ['public_key'],
        ];
    }

    /** @test */
    public function users_should_not_delete_keys(): void
    {
        $key = Key::factory()->create();

        $this
            ->actingAs($this->user)
            ->delete(route('keys.destroy', $key))
            ->assertForbidden();
    }

    /** @test */
    public function eliminators_should_delete_keys(): void
    {
        $this->user->givePermissionTo(Permissions::DeleteKeys);

        $key = Key::factory()->create();

        $this
            ->actingAs($this->user)
            ->delete(route('keys.destroy', $key))
            ->assertRedirect(route('keys.index'));

        $this->assertModelMissing($key);
    }
}
