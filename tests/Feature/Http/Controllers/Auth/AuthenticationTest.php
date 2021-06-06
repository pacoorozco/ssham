<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_displays_the_login_form(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertViewIs('auth.login');
    }

    /** @test */
    public function users_should_authenticate_using_login(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('i-love-laravel'),
        ]);

        $response = $this->post(route('login'), [
            'username' => $user->username,
            'password' => 'i-love-laravel',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(RouteServiceProvider::HOME);
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function users_should_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('i-love-laravel'),
        ]);

        $response = $this->post(route('login'), [
            'username' => $user->username,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }
}
