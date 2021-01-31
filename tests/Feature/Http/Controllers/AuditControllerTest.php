<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Activity;
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
