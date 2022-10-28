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
use App\Models\User;
use Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Larapacks\Setting\Setting;
use PacoOrozco\OpenSSH\PrivateKey;
use Tests\TestCase;
use Tests\Traits\InteractsWithPermissions;

class SettingsControllerTest extends TestCase
{
    use RefreshDatabase;
    use InteractsWithPermissions;

    const TEST_SETTINGS = [
        'authorized_keys' => '.ssh/authorized_keys',
        'private_key' => '-----BEGIN RSA PRIVATE KEY-----
MIIEogIBAAKCAQEA1rny3MS82I+Zfir8ZSinvzOPnuBs7WeGYNlD2tVIPcJuQwdo
R9weyko/nsNszQSQxIB5MbDUlt4r2ah98rJroqo2mGajKzZcZxS3kVk7hSaN+W8x
BGuG5hHnuaTajw2CjDSZ20UXSZ5I1E5clSXO53hBc1gRJiFfwmgcpsdnYf5SGSVC
MweYimamxJwa49gOD7BMvmTSB2M3B1dqhFpo2owjJk34no5YhyBgv+lZ3scGt1Bm
zuYfvUO9rI+czrBN6bSjd1kp2RxxhuANNyYBgpVfy7+9iXY/IvRbROh1ybkI4NHQ
9RvDF0c+XjkmEP7HpNdbb+lbIVEbSm0RxDm7eQIDAQABAoIBAE6/pVzn3iZCC9Xk
p+nljhemLj7jKa+rbvHn3GXOII9d1hhJCCqKhNRFhK2SGD3cNceLImdh5aVq14Qc
sgwm+SiP6jPcOG0po5u+UlBA/H4plJns+HoZgUCxQl2oIuuh1cXbqK1s2kyKe0U5
c+TTF912EfLeUnO8e6UNj6xupvCdqyj4D+/jm9wQtxS60kAfwVXpqGMv5Bi19Pcd
mfX79k0lFASlOE6n6wyRd3B0aDP9MkhWv1MKc3m7Mx9ncerAee1WiGmOXtKxrlii
8NT+0Vnk/4/2266eEr/O1j9rnhI2mMuSU3JaYa+WcmQgjYn8MCkiZQ2ls/wxMmF2
+kGaA7ECgYEA/qo2SDn+AkwuOWAXoCG9N/+K/SnblV9eqcld/azDtAL/8LA/cxbk
57xTVQUjg6SF4bm5h5/ckFZWmuOuTBGLq4EGr6GeNCENGfpp8wOwChuMvckuNWft
dAchNO3F7mcHc1C/M3nMknw3ki3VqdZDiDuZIEe+O3i1BRGzfyMVUTUCgYEA19oi
gfTTDkYubKG1lNqlHW3gM/7U2Kg1fIpThbTQRdL2r3c/Qv5dyeprLuEQtRD823BV
bJcnkc6pQfxvrM4d6AQbSf2vXgvQqN3gI9oELTnIsyteVeUqlIiWgyQV3ry97cYP
4KwliGn6uNjbspDWjj9TMMWH6NwS9T/QklB9LbUCgYBAOI5t0moTDEEP6QKsODXu
fDwBsd+VILLPoF9ajInhyGvgt6GYlWpuYhiltSEKnp/Al8SIxjCqGYvHjakzt60A
OA6Glfw+ayNRrMuxI/nt4pqwdZ54i1ffTbj7Dwc+Iq9HTlKZWZrUSgz9iR97r/Ph
1B2+fTkk6EDGIXuhkShcDQKBgGQAASCn5eelB/j36gHuUbSSpN4x3zYIp87s3skA
/cR7eiHMYxiMc7MJ0WJEjtLz7dFT9595X7GjRrL1Wl96lkxZKrNavhYZ7y9sq1yw
smKpdJvdKbUtmE58AQ78ds1cL7mqmsyBtFnAj5F3lkDh7SS1nbroqJBu9LF+QQio
FXZJAoGAeO3ixxEgnfAi9KRLclX8CG651ue4PhldllO82z4y1C8BIUD1SrQEIFU7
BBHAb40w/ou64RHttUVGSzNr7vXceadp57RVWqnMpQiWKQX3iDnxa+CCjIwyeKPg
c6i7uxhddb2j2GasjwJS0+KCE/csVWZ617lLWT0+U5SK7Aatjes=
-----END RSA PRIVATE KEY-----',
        'public_key' => 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDWufLcxLzYj5l+KvxlKKe/M4+e4GztZ4Zg2UPa1Ug9wm5DB2hH3B7KSj+ew2zNBJDEgHkxsNSW3ivZqH3ysmuiqjaYZqMrNlxnFLeRWTuFJo35bzEEa4bmEee5pNqPDYKMNJnbRRdJnkjUTlyVJc7neEFzWBEmIV/CaBymx2dh/lIZJUIzB5iKZqbEnBrj2A4PsEy+ZNIHYzcHV2qEWmjajCMmTfiejliHIGC/6Vnexwa3UGbO5h+9Q72sj5zOsE3ptKN3WSnZHHGG4A03JgGClV/Lv72Jdj8i9FtE6HXJuQjg0dD1G8MXRz5eOSYQ/sek11tv6VshURtKbRHEObt5 root@ssham',
        'ssh_timeout' => '5',
        'ssh_port' => '22',
        'mixed_mode' => true,
        'ssham_file' => '.ssh/authorized_keys-ssham',
        'non_ssham_file' => '.ssh/authorized_keys-non-ssham',
        'cmd_remote_updater' => '.ssh/ssham-remote-updater.sh',
    ];

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->setupRolesAndPermissions();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function users_should_see_the_index_view(): void
    {
        setting()->set(self::TEST_SETTINGS);

        $this
            ->actingAs($this->user)
            ->get(route('settings.index'))
            ->assertSuccessful()
            ->assertViewIs('settings.index')
            ->assertViewHas('settings', setting()->all());
    }

    /** @test */
    public function users_should_not_see_the_edit_settings_form(): void
    {
        setting()->set(self::TEST_SETTINGS);

        $this
            ->actingAs($this->user)
            ->get(route('settings.edit'))
            ->assertForbidden();
    }

    /** @test */
    public function editors_should_see_the_edit_settings_form(): void
    {
        $this->user->givePermissionTo(Permissions::EditSettings);

        setting()->set(self::TEST_SETTINGS);

        $this
            ->actingAs($this->user)
            ->get(route('settings.edit'))
            ->assertSuccessful()
            ->assertViewIs('settings.edit')
            ->assertViewHas('settings', setting()->all());
    }

    /** @test */
    public function users_should_not_update_the_settings(): void
    {
        Setting::set(self::TEST_SETTINGS);

        $privateKey = PrivateKey::generate();
        $publicKey = $privateKey->getPublicKey();

        $formData = [
            'authorized_keys' => 'foo_authorized_keys',
            'private_key' => trim($privateKey),
            'public_key' => trim($publicKey),
            'ssh_timeout' => 10,
            'ssh_port' => 2022,
            'mixed_mode' => false,
            'ssham_file' => 'foo_ssham_file',
            'non_ssham_file' => 'foo_non_ssham_file',
            'cmd_remote_updater' => 'foo_cmd_remote_updater',
        ];

        $this
            ->actingAs($this->user)
            ->put(route('settings.update', $formData))
            ->assertForbidden();

        $this->assertEquals(self::TEST_SETTINGS, Setting::all()->toArray());
    }

    /** @test */
    public function editors_should_update_the_settings(): void
    {
        $this->user->givePermissionTo(Permissions::EditSettings);

        setting()->set(self::TEST_SETTINGS);

        $privateKey = PrivateKey::generate();
        $publicKey = $privateKey->getPublicKey();

        $formData = [
            'authorized_keys' => 'foo_authorized_keys',
            'private_key' => trim($privateKey),
            'public_key' => trim($publicKey),
            'ssh_timeout' => 10,
            'ssh_port' => 2022,
            'mixed_mode' => false,
            'ssham_file' => 'foo_ssham_file',
            'non_ssham_file' => 'foo_non_ssham_file',
            'cmd_remote_updater' => 'foo_cmd_remote_updater',
        ];

        $this
            ->actingAs($this->user)
            ->put(route('settings.update', $formData))
            ->assertRedirect(route('settings.index'))
            ->assertSessionHasNoErrors();

        $this->assertEquals($formData, setting()->all()->toArray());
    }

    /**
     * @test
     * @dataProvider provideWrongDataForSettingsModification
     */
    public function editors_should_get_errors_when_updating_the_settings_with_wrong_data(
        array $data,
        array $errors,
    ): void {
        $this->user->givePermissionTo(Permissions::EditSettings);

        setting()->set(self::TEST_SETTINGS);

        $settings = self::TEST_SETTINGS;

        $formData = [
            'authorized_keys' => $data['authorized_keys'] ?? $settings['authorized_keys'],
            'private_key' => $data['private_key'] ?? $settings['private_key'],
            'public_key' => $data['public_key'] ?? $settings['public_key'],
            'ssh_timeout' => $data['ssh_timeout'] ?? $settings['ssh_timeout'],
            'ssh_port' => $data['ssh_port'] ?? $settings['ssh_port'],
            'mixed_mode' => $data['mixed_mode'] ?? $settings['mixed_mode'],
            'ssham_file' => $data['ssham_file'] ?? $settings['ssham_file'],
            'non_ssham_file' => $data['non_ssham_file'] ?? $settings['non_ssham_file'],
            'cmd_remote_updater' => $data['cmd_remote_updater'] ?? $settings['cmd_remote_updater'],
        ];

        $this
            ->actingAs($this->user)
            ->put(route('settings.update', $formData))
            ->assertInvalid($errors);

        $this->assertEquals($settings, setting()->all()->toArray());
    }

    public function provideWrongDataForSettingsModification(): Generator
    {
        yield 'authorized_keys is empty' => [
            'data' => [
                'authorized_keys' => '',
            ],
            'errors' => ['authorized_keys'],
        ];

        yield 'private_key is empty' => [
            'data' => [
                'private_key' => '',
            ],
            'errors' => ['private_key'],
        ];

        yield 'private_key ! valid' => [
            'data' => [
                'private_key' => 'no-valid-private-key',
            ],
            'errors' => ['private_key'],
        ];

        yield 'ssh_port ! valid' => [
            'data' => [
                'ssh_port' => 'no-numeric',
            ],
            'errors' => ['ssh_port'],
        ];

        yield 'ssh_timeout ! valid' => [
            'data' => [
                'ssh_timeout' => 'no-numeric',
            ],
            'errors' => ['ssh_timeout'],
        ];

        yield 'ssh_timeout < 5' => [
            'data' => [
                'ssh_timeout' => '4',
            ],
            'errors' => ['ssh_timeout'],
        ];

        yield 'ssh_timeout < 15' => [
            'data' => [
                'ssh_timeout' => '16',
            ],
            'errors' => ['ssh_timeout'],
        ];

        yield 'mixed_mode ! valid' => [
            'data' => [
                'mixed_mode' => 'non-boolean',
            ],
            'errors' => ['mixed_mode'],
        ];

        yield 'ssham_file is empty' => [
            'data' => [
                'ssham_file' => '',
            ],
            'errors' => ['ssham_file'],
        ];

        yield 'non_ssham_file is empty' => [
            'data' => [
                'non_ssham_file' => '',
            ],
            'errors' => ['non_ssham_file'],
        ];

        yield 'cmd_remote_updater is empty' => [
            'data' => [
                'cmd_remote_updater' => '',
            ],
            'errors' => ['cmd_remote_updater'],
        ];
    }
}
