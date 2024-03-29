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

use PHPUnit\Framework\Attributes\Test;
use App\Enums\Permissions;
use App\Models\Key;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\Feature\InteractsWithPermissions;
use Tests\Feature\TestCase;

final class KeyDataTablesControllerTest extends TestCase
{
    use InteractsWithPermissions;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setupRolesAndPermissions();

        $this->user = User::factory()->create();
    }

    #[Test]
    public function viewers_should_get_error_when_getting_data_tables_data_with_non_AJAX_requests(): void
    {
        $this->user->givePermissionTo(Permissions::ViewKeys);

        $this
            ->actingAs($this->user)
            ->get(route('keys.data'))
            ->assertForbidden();
    }

    #[Test]
    public function users_should_not_get_data_tables_data(): void
    {
        $this
            ->actingAs($this->user)
            ->ajaxGet(route('keys.data'))
            ->assertForbidden();
    }

    #[Test]
    public function viewers_should_get_data_tables_data(): void
    {
        $this->user->givePermissionTo(Permissions::ViewKeys);

        $keys = Key::factory()
            ->count(2)
            ->state(new Sequence(
                ['enabled' => true],
                ['enabled' => false],
            ))
            ->create();

        $this
            ->actingAs($this->user)
            ->ajaxGet(route('keys.data'))
            ->assertSuccessful()
            ->assertJsonCount(count($keys), 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'name',
                        'fingerprint',
                        'groups',
                        'actions',
                    ],
                ],
            ]);
    }
}
