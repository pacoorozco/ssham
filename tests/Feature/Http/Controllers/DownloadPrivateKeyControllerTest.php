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
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DownloadPrivateKeyControllerTest extends TestCase
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
    public function downloadPrivateKey_method_removes_private_key_after_it_is_downloaded(): void
    {
        $key = Key::factory()
            ->create();
        $wantPrivateKeyContent = $key->private;

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('keys.download', $key));

        $response->assertSuccessful();
        $response->assertHeader('Content-Type', 'application/pkcs8');
        $response->assertHeader('Content-Disposition', 'attachment; filename=id_rsa');
        $this->assertEquals($wantPrivateKeyContent, $response->streamedContent());
        $this->assertDatabaseHas('keys', [
            'id' => $key->id,
            'private' => null,
        ]);
    }

    /** @test */
    public function downloadPrivateKey_method_returns_error_when_private_key_is_not_present(): void
    {
        $key = Key::factory()
            ->create([
                'private' => null,
            ]);

        $response = $this
            ->actingAs($this->user_to_act_as)
            ->get(route('keys.download', $key));

        $response->assertNotFound();
    }
}
