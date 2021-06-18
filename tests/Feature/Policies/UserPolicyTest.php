<?php

namespace Tests\Feature\Policies;

use App\Enums\Permissions;
use App\Enums\Roles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use Tests\Traits\InteractsWithPermissions;

class UserPolicyTest extends TestCase
{
    use RefreshDatabase;
    use InteractsWithPermissions;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->enablePermissionsCheck();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function can_not_create_users_without_the_proper_permission(): void
    {
        $this->user->givePermissionTo(Permissions::asArray());
        $this->user->revokePermissionTo(Permissions::EditUsers);

        $response = $this->createUserRequestAs($this->user);

        $response->assertForbidden();
    }

    private function createUserRequestAs(User $user): TestResponse
    {
        /** @var User $want */
        $want = User::factory()->make();

        return $this->actingAs($user)
            ->post(route('users.store'), [
                'username' => $want->username,
                'email' => $want->email,
                'password' => 'secret123',
                'password_confirmation' => 'secret123',
                'role' => Roles::Operator,
            ]);
    }

    /** @test */
    public function can_create_users_with_the_proper_permission(): void
    {
        $this->user->syncPermissions(Permissions::EditUsers);

        $response = $this->createUserRequestAs($this->user);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /** @test */
    public function can_not_edit_users_without_the_proper_permission(): void
    {
        $this->user->givePermissionTo(Permissions::asArray());
        $this->user->revokePermissionTo(Permissions::EditUsers);

        $response = $this->editUserRequestAs($this->user);

        $response->assertForbidden();
    }

    private function editUserRequestAs(User $user, ?User $testUser = null): TestResponse
    {
        $testUser = $testUser ?? User::factory()->create();
        $testUser->syncRoles(Roles::Auditor);

        /** @var User $want */
        $want = User::factory()->make();

        return $this->actingAs($user)
            ->put(route('users.update', $testUser), [
                'email' => $want->email,
                'enabled' => $want->enabled,
                'role' => Roles::Operator,
            ]);
    }

    /** @test */
    public function can_edit_users_with_the_proper_permission(): void
    {
        $this->user->syncPermissions(Permissions::EditUsers);

        $response = $this->editUserRequestAs($this->user);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /** @test */
    public function anyone_can_edit_its_own_user(): void
    {
        $this->user->givePermissionTo(Permissions::asArray());
        $this->user->revokePermissionTo(Permissions::EditUsers);

        $response = $this->editUserRequestAs($this->user, $this->user);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /** @test */
    public function can_not_delete_users_without_the_poper_permission(): void
    {
        $this->user->givePermissionTo(Permissions::asArray());
        $this->user->revokePermissionTo(Permissions::DeleteUsers);

        $response = $this->deleteUserRequestAs($this->user);

        $response->assertForbidden();
    }

    private function deleteUserRequestAs(User $user, ?User $testUser = null): TestResponse
    {
        $testUser = $testUser ?? User::factory()->create();

        return $this->actingAs($user)
            ->delete(route('users.destroy', $testUser));
    }

    /** @test */
    public function can_delete_users_with_the_proper_permission(): void
    {
        $this->user->syncPermissions(Permissions::DeleteUsers);

        $response = $this->deleteUserRequestAs($this->user);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /** @test */
    public function user_can_not_delete_its_own_user(): void
    {
        $this->user->syncPermissions(Permissions::DeleteUsers);

        $response = $this->deleteUserRequestAs($this->user, $this->user);

        $response->assertForbidden();
    }
}
