<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_a_view()
    {
        $user = User::factory()
            ->create();

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertStatus(200);
    }

    public function test_index_without_auth_returns_login_form()
    {
        $response = $this->get(route('home'));

        $response->assertRedirect(route('login'));
    }
}
