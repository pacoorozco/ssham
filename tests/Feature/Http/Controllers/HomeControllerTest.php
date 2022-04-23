<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_should_see_the_dashboard(): void
    {
        $user = User::factory()
            ->create();

        $this->actingAs($user)
            ->get(route('home'))
            ->assertSuccessful();
    }

    /** @test */
    public function users_should_not_see_the_dashboard(): void
    {
        $this->get(route('home'))
            ->assertRedirect(route('login'));
    }
}
