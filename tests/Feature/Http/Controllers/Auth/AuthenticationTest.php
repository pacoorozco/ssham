<?php

namespace Tests\Feature\Http\Controllers\Auth;

use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Response;
use Tests\Feature\TestCase;

final class AuthenticationTest extends TestCase
{
    #[Test]
    public function login_displays_the_login_form(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertViewIs('auth.login');
    }

    #[Test]
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

    #[Test]
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
