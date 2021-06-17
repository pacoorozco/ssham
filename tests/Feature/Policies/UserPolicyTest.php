<?php

namespace Tests\Feature\Policies;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Tests\Traits\InteractsWithPermissions;

class UserPolicyTest extends TestCase
{
    use RefreshDatabase;
    use InteractsWithPermissions;

    private User $auditor;

    private User $operator;

    private User $admin;

    private User $superAdmin;

    public function setUp(): void
    {
        parent::setUp();

        $this->enablePermissionsCheck();

        $this->auditor = User::factory()->create();
        $this->auditor->assignRole(Roles::Auditor);

        $this->operator = User::factory()->create();
        $this->operator->assignRole(Roles::Operator);

        $this->admin = User::factory()->create();
        $this->admin->assignRole(Roles::Admin);

        $this->superAdmin = User::factory()->create();
        $this->superAdmin->assignRole(Roles::SuperAdmin);
    }

    /** @test */
    public function auditor_can_not_create_users(): void
    {
        $response = $this->createUserRequestAs($this->auditor);

        $response->assertForbidden();
    }

    /** @test */
    public function operator_can_not_create_users(): void
    {
        $response = $this->createUserRequestAs($this->operator);

        $response->assertForbidden();
    }

    /** @test */
    public function admin_can_not_create_users(): void
    {
        $response = $this->createUserRequestAs($this->admin);

        $response->assertForbidden();
    }

    /** @test */
    public function superadmin_can_create_users(): void
    {
        $response = $this->createUserRequestAs($this->superAdmin);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /** @test */
    public function auditor_can_not_edit_users(): void
    {
        $response = $this->editUserRequestAs($this->auditor);

        $response->assertForbidden();
    }

    /** @test */
    public function auditor_can_edit_its_own_user(): void
    {
        $response = $this->editUserRequestAs($this->auditor, $this->auditor);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /** @test */
    public function operator_can_not_edit_users(): void
    {
        $response = $this->editUserRequestAs($this->operator);

        $response->assertForbidden();
    }

    /** @test */
    public function operator_can_edit_its_own_user(): void
    {
        $response = $this->editUserRequestAs($this->operator, $this->operator);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /** @test */
    public function admin_can_not_edit_users(): void
    {
        $response = $this->editUserRequestAs($this->admin);

        $response->assertForbidden();
    }

    /** @test */
    public function admin_can_edit_its_own_user(): void
    {
        $response = $this->editUserRequestAs($this->admin, $this->admin);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /** @test */
    public function superadmin_can_edit_users(): void
    {
        $response = $this->editUserRequestAs($this->superAdmin);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /** @test */
    public function auditor_can_not_delete_users(): void
    {
        $response = $this->deleteUserRequestAs($this->auditor);

        $response->assertForbidden();
    }

    /** @test */
    public function operator_can_not_delete_users(): void
    {
        $response = $this->deleteUserRequestAs($this->operator);

        $response->assertForbidden();
    }

    /** @test */
    public function admin_can_not_delete_users(): void
    {
        $response = $this->deleteUserRequestAs($this->admin);

        $response->assertForbidden();
    }

    /** @test */
    public function superadmin_can_delete_users(): void
    {
        $response = $this->deleteUserRequestAs($this->superAdmin);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /** @test */
    public function superadmin_can_not_delete_its_own_user(): void
    {
        $response = $this->deleteUserRequestAs($this->superAdmin, $this->superAdmin);

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

    private function deleteUserRequestAs(User $user, ?User $testUser = null): TestResponse
    {
        $testUser = $testUser ?? User::factory()->create();

        return $this->actingAs($user)
            ->delete(route('users.destroy', $testUser));
    }
}
