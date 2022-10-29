<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Response;
use Tests\Feature\TestCase;

class PasswordConfirmationTest extends TestCase
{
    /** @test */
    public function confirm_password_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('password.confirm'));

        $response->assertOk();
    }

    /** @test */
    public function password_can_be_confirmed(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('i-love-laravel'),
        ]);

        $response = $this->actingAs($user)
            ->post(route('password.confirm'), [
                'password' => 'i-love-laravel',
            ]);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function password_is_not_confirmed_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('i-love-laravel'),
        ]);

        $response = $this->actingAs($user)
            ->post(route('password.confirm'), [
                'password' => 'wrong-password',
            ]);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHasErrors();
    }
}
