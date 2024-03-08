<?php

namespace Tests\Feature\Http\Controllers;

use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use Tests\Feature\TestCase;

class HomeControllerTest extends TestCase
{
    #[Test]
    public function users_should_see_the_dashboard(): void
    {
        $user = User::factory()
            ->create();

        $this->actingAs($user)
            ->get(route('home'))
            ->assertSuccessful();
    }

    #[Test]
    public function users_should_not_see_the_dashboard(): void
    {
        $this->get(route('home'))
            ->assertRedirect(route('login'));
    }
}
