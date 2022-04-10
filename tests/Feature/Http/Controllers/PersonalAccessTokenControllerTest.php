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
 *
 * @link        https://github.com/pacoorozco/ssham
 */

namespace Tests\Feature\Http\Controllers;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\InteractsWithPermissions;

class PersonalAccessTokenControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use InteractsWithPermissions;

    public function setUp(): void
    {
        parent::setUp();

        $this->setupRolesAndPermissions();
    }

    /** @test */
    public function users_can_access_to_its_own_token_data(): void
    {
        $user = User::factory()->create();
        $expectedTokenName = $this->faker->name();
        $user->createToken($expectedTokenName);

        $response = $this
            ->actingAs($user)
            ->get(route('users.tokens.index', $user));

        $response->assertSuccessful();
        $response->assertViewIs('user.personal_access_tokens.show');
        $response->assertSee($expectedTokenName);
    }

    /** @test */
    public function superadmins_can_access_to_others_token_data(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole(Roles::SuperAdmin);

        $viewedUser = User::factory()->create();
        $expectedTokenName = $this->faker->name();
        $viewedUser->createToken($expectedTokenName);

        $response = $this
            ->actingAs($superAdmin)
            ->get(route('users.tokens.index', $viewedUser));

        $response->assertSuccessful();
        $response->assertViewIs('user.personal_access_tokens.show');
        $response->assertSee($expectedTokenName);
    }

    /** @test */
    public function superadmins_can_create_others_token(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole(Roles::SuperAdmin);

        /** @var User $viewedUser */
        $viewedUser = User::factory()->create();

        $expectedTokenName = $this->faker->name();

        $response = $this
            ->actingAs($superAdmin)
            ->post(route('users.tokens.store', $viewedUser), [
                'name' => $expectedTokenName,
            ]);

        $response->assertRedirect(route('users.tokens.index', $viewedUser));
        $this->assertTrue($this->userHasToken($viewedUser, $expectedTokenName));
    }

    /** @test */
    public function user_can_create_its_own_token(): void
    {
        $user = User::factory()->create();

        $expectedTokenName = $this->faker->name();

        $response = $this
            ->actingAs($user)
            ->post(route('users.tokens.store', $user), [
                'name' => $expectedTokenName,
            ]);

        $response->assertRedirect(route('users.tokens.index', $user));
        $this->assertTrue($this->userHasToken($user, $expectedTokenName));
    }

    /** @test */
    public function user_can_revoke_its_own_token(): void
    {
        $user = User::factory()->create();

        $expectedTokenName = $this->faker->name();
        $token = $user->createToken($expectedTokenName)->accessToken;

        $response = $this
            ->actingAs($user)
            ->delete(route('tokens.destroy', $token));

        $response->assertRedirect(route('users.tokens.index', $user));
        $this->assertFalse($this->userHasToken($user, $expectedTokenName));
    }

    /** @test */
    public function superadmins_can_revoke_others_token(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole(Roles::SuperAdmin);

        $viewedUser = User::factory()->create();

        $expectedTokenName = $this->faker->name();
        $token = $viewedUser->createToken($expectedTokenName)->accessToken;

        $response = $this
            ->actingAs($superAdmin)
            ->delete(route('tokens.destroy', $token));

        $response->assertRedirect(route('users.tokens.index', $viewedUser));
        $this->assertFalse($this->userHasToken($viewedUser, $expectedTokenName));
    }

    private function userHasToken(User $user, string $tokenName): bool
    {
        $tokens = $user->tokens()->pluck('name');

        return false !== $tokens->search($tokenName);
    }
}
