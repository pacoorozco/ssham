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
 *
 * @link        https://github.com/pacoorozco/ssham
 */

namespace Tests\Feature\Http\Controllers;

use App\Enums\Permissions;
use App\Models\Host;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\Feature\InteractsWithPermissions;
use Tests\Feature\TestCase;

class HostDataTableControllerTest extends TestCase
{
    use InteractsWithPermissions;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->setupRolesAndPermissions();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function viewers_should_get_error_when_getting_data_tables_data_with_non_AJAX_requests(): void
    {
        $this
            ->actingAs($this->user)
            ->get(route('hosts.data'))
            ->assertForbidden();
    }

    /** @test */
    public function users_should_not_get_data_tables_data(): void
    {
        $this
            ->actingAs($this->user)
            ->ajaxGet(route('hosts.data'))
            ->assertForbidden();
    }

    /** @test */
    public function viewers_should_get_data_tables_data(): void
    {
        $this->user->givePermissionTo(Permissions::ViewHosts);

        $hosts = Host::factory()
            ->count(2)
            ->state(new Sequence(
                ['enabled' => true],
                ['enabled' => false],
            ))
            ->state(new Sequence(
                ['synced' => true],
                ['synced' => false],
            ))
            ->create();

        $this
            ->actingAs($this->user)
            ->ajaxGet(route('hosts.data'))
            ->assertSuccessful()
            ->assertJsonCount(count($hosts), 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'hostname',
                        'username',
                        'groups',
                        'actions',
                    ],
                ],
            ]);
    }
}
