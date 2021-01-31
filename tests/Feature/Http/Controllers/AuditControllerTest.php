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
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_calling_index_should_return_index_view()
    {
        $user = User::factory()
            ->create();

        $response = $this->actingAs($user)
            ->get(route('audit'));

        $response->assertViewIs('audit.index');
    }

    public function test_calling_index_without_auth_should_return_login_route()
    {
        $response = $this->get(route('audit'));

        $response->assertRedirect(route('login'));
    }

    public function test_calling_data_should_return_a_json()
    {
        $user = User::factory()
            ->create();

        $response = $this->actingAs($user)
            ->ajaxGet(route('audit.data'));

        $response->assertJsonCount(5);
    }

    public function test_calling_data_without_ajax_should_return_error()
    {
        $user = User::factory()
            ->create();

        $response = $this->actingAs($user)
            ->get(route('audit.data'));

        $response->assertForbidden();
    }

    public function test_calling_data_without_auth_should_return_login_route()
    {
        $response = $this->ajaxGet(route('audit.data'));

        $response->assertRedirect(route('login'));
    }
}
