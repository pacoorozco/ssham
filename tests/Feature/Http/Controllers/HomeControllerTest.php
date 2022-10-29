<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Tests\Feature\TestCase;

class HomeControllerTest extends TestCase
{
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
