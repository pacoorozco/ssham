<?php
/*
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2021 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2017 - 2021 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/ssham
 */

namespace Tests\Feature\Http\Controllers;

use PHPUnit\Framework\Attributes\Test;
use App\Models\Host;
use App\Models\User;
use Tests\Feature\InteractsWithPermissions;
use Tests\Feature\TestCase;

final class AuditDataTablesControllerTest extends TestCase
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
    public function users_should_get_error_when_getting_data_tables_data_with_non_AJAX_requests(): void
    {
        $this
            ->actingAs($this->user)
            ->get(route('audit.data'))
            ->assertForbidden();
    }

    #[Test]
    public function users_should_get_data_tables_data(): void
    {
        Host::factory()->count(4)->create();

        $this
            ->actingAs($this->user)
            ->ajaxGet(route('audit.data'))
            ->assertSuccessful()
            ->assertJsonCount(5, 'data'); // 4 Hosts + 1 User (see setUp)
    }
}
