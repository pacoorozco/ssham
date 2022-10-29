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
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\InteractsWithPermissions;
use Tests\Feature\TestCase;

class PersonalAccessTokenControllerTest extends TestCase
{
    use WithFaker;
    use InteractsWithPermissions;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->setupRolesAndPermissions();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function users_should_view_its_own_tokens(): void
    {
        $expectedTokenName = $this->faker->name();
        $this->user->createToken($expectedTokenName);

        $this
            ->actingAs($this->user)
            ->get(route('users.tokens.index', $this->user))
            ->assertSuccessful()
            ->assertViewIs('user.personal_access_tokens.show')
            ->assertSee($expectedTokenName);
    }

    /** @test */
    public function users_should_see_the_token_creation_form(): void
    {
        $this
            ->actingAs($this->user)
            ->get(route('users.tokens.create', $this->user))
            ->assertSuccessful()
            ->assertViewIs('user.personal_access_tokens.create');
    }

    /** @test */
    public function users_should_not_see_the_others_token_creation_form(): void
    {
        $otherUser = User::factory()->create();

        $this
            ->actingAs($this->user)
            ->get(route('users.tokens.create', $otherUser))
            ->assertForbidden();
    }

    /** @test */
    public function super_admins_should_see_others_tokens(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole(Roles::SuperAdmin);

        $expectedTokenName = $this->faker->name();
        $this->user->createToken($expectedTokenName);

        $this
            ->actingAs($superAdmin)
            ->get(route('users.tokens.index', $this->user))
            ->assertSuccessful()
            ->assertViewIs('user.personal_access_tokens.show')
            ->assertSee($expectedTokenName);
    }

    /** @test */
    public function super_admins_should_see_the_others_token_creation_form(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole(Roles::SuperAdmin);

        $this
            ->actingAs($superAdmin)
            ->get(route('users.tokens.create', $this->user))
            ->assertSuccessful()
            ->assertViewIs('user.personal_access_tokens.create');
    }

    /** @test */
    public function super_admins_should_create_others_tokens(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole(Roles::SuperAdmin);

        $expectedTokenName = $this->faker->name();

        $this
            ->actingAs($superAdmin)
            ->post(route('users.tokens.store', $this->user), [
                'name' => $expectedTokenName,
            ])
            ->assertRedirect(route('users.tokens.index', $this->user));

        $this->assertTrue($this->userHasToken($this->user, $expectedTokenName));
    }

    private function userHasToken(User $user, string $tokenName): bool
    {
        $tokens = $user->tokens()->pluck('name');

        return false !== $tokens->search($tokenName);
    }

    /** @test */
    public function users_should_create_its_own_tokens(): void
    {
        $expectedTokenName = $this->faker->name();

        $this
            ->actingAs($this->user)
            ->post(route('users.tokens.store', $this->user), [
                'name' => $expectedTokenName,
            ])
            ->assertRedirect(route('users.tokens.index', $this->user));

        $this->assertTrue($this->userHasToken($this->user, $expectedTokenName));
    }

    /** @test */
    public function users_should_revoke_its_own_tokens(): void
    {
        $expectedTokenName = $this->faker->name();
        $token = $this->user->createToken($expectedTokenName)->accessToken;

        $this
            ->actingAs($this->user)
            ->delete(route('tokens.destroy', $token))
            ->assertRedirect(route('users.tokens.index', $this->user));

        $this->assertFalse($this->userHasToken($this->user, $expectedTokenName));
    }

    /** @test */
    public function super_admins_should_revoke_others_tokens(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole(Roles::SuperAdmin);

        $expectedTokenName = $this->faker->name();
        $token = $this->user->createToken($expectedTokenName)->accessToken;

        $this
            ->actingAs($superAdmin)
            ->delete(route('tokens.destroy', $token))
            ->assertRedirect(route('users.tokens.index', $this->user));

        $this->assertFalse($this->userHasToken($this->user, $expectedTokenName));
    }
}
