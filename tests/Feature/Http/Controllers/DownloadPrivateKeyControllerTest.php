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

use App\Enums\Permissions;
use App\Models\Key;
use App\Models\User;
use Tests\Feature\InteractsWithPermissions;
use Tests\Feature\TestCase;

class DownloadPrivateKeyControllerTest extends TestCase
{
    use InteractsWithPermissions;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setupRolesAndPermissions();

        $this->user = User::factory()->create();
    }

    /**
     * @test
     *
     * @dataProvider providePrivateKeysToDownload
     */
    public function users_should_not_download_existing_private_keys(
        array $data,
    ): void {
        $key = Key::factory()
            ->create($data);

        $this
            ->actingAs($this->user)
            ->get(route('keys.download', $key))
            ->assertForbidden();

        $this->assertModelExists($key);
    }

    public static function providePrivateKeysToDownload(): \Generator
    {
        yield 'private key ! exist' => [
            'data' => [
                'private' => '',
            ],
        ];

        yield 'private key exist' => [
            'data' => [],
        ];
    }

    /** @test */
    public function editors_should_remove_private_key_after_downloading_it(): void
    {
        $this->user->givePermissionTo(Permissions::EditKeys);

        $key = Key::factory()->create();

        $wantPrivateKeyContent = $key->private;

        $response = $this
            ->actingAs($this->user)
            ->get(route('keys.download', $key))
            ->assertSuccessful()
            ->assertHeader('Content-Type', 'application/pkcs8')
            ->assertDownload('id_rsa');

        $this->assertEquals($wantPrivateKeyContent, $response->streamedContent());

        // Second time should response 404.
        $this
            ->actingAs($this->user)
            ->get(route('keys.download', $key))
            ->assertNotFound();
    }

    /** @test */
    public function editors_should_get_error_when_private_key_does_not_exist(): void
    {
        $this->user->givePermissionTo(Permissions::EditKeys);

        $key = Key::factory()
            ->create([
                'private' => '',
            ]);

        $this
            ->actingAs($this->user)
            ->get(route('keys.download', $key))
            ->assertNotFound();
    }
}
