<?php
/**
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2020 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 - 2020 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class PersonalAccessTokenControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()
            ->create();
    }

    /** @test */
    public function user_can_access_to_its_own_token_data(): void
    {
        $expectedTokenName = $this->faker->name();
        $this->user->createToken($expectedTokenName);

        $response = $this
            ->actingAs($this->user)
            ->get(route('users.tokens.index', $this->user));

        $response->assertSuccessful();
        $response->assertViewIs('user.personal_access_tokens.show');
        $response->assertSee($expectedTokenName);
    }

    /** @test */
    public function admin_can_access_to_others_token_data(): void
    {
        $viewedUser = User::factory()
            ->create();
        $expectedTokenName = $this->faker->name();
        $viewedUser->createToken($expectedTokenName);

        $response = $this
            ->actingAs($this->user)
            ->get(route('users.tokens.index', $viewedUser));

        $response->assertSuccessful();
        $response->assertViewIs('user.personal_access_tokens.show');
        $response->assertSee($expectedTokenName);
    }

    /** @test */
    public function admin_can_create_others_token(): void
    {
        $expectedTokenName = $this->faker->name();
        $formData = [
            'name' => $expectedTokenName,
        ];
        $viewedUser = User::factory()
            ->create();

        $response = $this
            ->actingAs($this->user)
            ->post(route('users.tokens.store', $viewedUser), $formData);

        $response->assertRedirect(route('users.tokens.index', $viewedUser));
        $this->assertTrue($this->tokenExists($expectedTokenName, $viewedUser->tokens));
    }

    /** @test */
    public function user_can_create_its_own_token(): void
    {
        $expectedTokenName = $this->faker->name();
        $formData = [
            'name' => $expectedTokenName,
        ];

        $response = $this
            ->actingAs($this->user)
            ->post(route('users.tokens.store', $this->user), $formData);

        $response->assertRedirect(route('users.tokens.index', $this->user));
        $this->assertTrue($this->tokenExists($expectedTokenName, $this->user->tokens));
    }

    /** @test */
    public function user_can_revoke_its_own_token(): void
    {
        $expectedTokenName = $this->faker->name();
        $token = $this->user->createToken($expectedTokenName)->accessToken;

        $response = $this
            ->actingAs($this->user)
            ->delete(route('tokens.destroy', $token));

        $response->assertRedirect(route('users.tokens.index', $this->user));
        $this->assertFalse($this->tokenExists($expectedTokenName, $this->user->tokens));
    }

    /** @test */
    public function admin_can_revoke_others_token(): void
    {
        $viewedUser = User::factory()
            ->create();
        $expectedTokenName = $this->faker->name();
        $token = $viewedUser->createToken($expectedTokenName)->accessToken;

        $response = $this
            ->actingAs($this->user)
            ->delete(route('tokens.destroy', $token));

        $response->assertRedirect(route('users.tokens.index', $viewedUser));
        $this->assertFalse($this->tokenExists($expectedTokenName, $viewedUser->tokens));
    }

    private function tokenExists(string $name, Collection $tokens): bool
    {
        return $tokens->contains('name', $name);
    }
}
