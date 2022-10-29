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

use App\Models\User;
use Tests\Feature\InteractsWithPermissions;
use Tests\Feature\TestCase;

class AuditControllerTest extends TestCase
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
    public function users_should_see_the_index_view(): void
    {
        $this
            ->actingAs($this->user)
            ->get(route('audit'))
            ->assertSuccessful()
            ->assertViewIs('audit.index');
    }

    /** @test */
    public function guests_should_not_see_the_index_view(): void
    {
        $this
            ->get(route('audit'))
            ->assertRedirect(route('login'));
    }
}
