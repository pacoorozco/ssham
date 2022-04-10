<?php

namespace Tests\Feature\Policies;

use App\Enums\Permissions;
use App\Models\Key;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use Tests\Traits\InteractsWithPermissions;

class KeyPolicyTest extends TestCase
{
    use RefreshDatabase;
    use InteractsWithPermissions;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->setupRolesAndPermissions();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function can_not_create_keys_without_the_proper_permission(): void
    {
        $this->user->givePermissionTo(Permissions::asArray());
        $this->user->revokePermissionTo(Permissions::EditKeys);

        $response = $this->createKeyRequestAs($this->user);

        $response->assertForbidden();
    }

    private function createKeyRequestAs(User $user): TestResponse
    {
        /** @var Key $want */
        $want = Key::factory()->make();

        return $this->actingAs($user)
            ->post(route('keys.store'), [
                'hostname' => $want->hostname,
                'username' => $want->username,
                'operation' => 'create',
            ]);
    }

    /** @test */
    public function can_create_keys_with_the_proper_permission(): void
    {
        $this->user->syncPermissions(Permissions::EditKeys);
        $response = $this->createKeyRequestAs($this->user);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /** @test */
    public function can_not_edit_keys_without_the_proper_permission(): void
    {
        $this->user->givePermissionTo(Permissions::asArray());
        $this->user->revokePermissionTo(Permissions::EditKeys);
        $key = Key::factory()->create();

        $response = $this->editKeyRequestAs($this->user, $key);

        $response->assertForbidden();
    }

    private function editKeyRequestAs(User $user, Key $key): TestResponse
    {
        /** @var Key $want */
        $want = Key::factory()->make();

        return $this->actingAs($user)
            ->put(route('keys.update', $key), [
                'enabled' => $want->enabled,
                'operation' => 'import',
                'public_key' => $want->public,
            ]);
    }

    /** @test */
    public function can_edit_keys_with_the_proper_permission(): void
    {
        $this->user->syncPermissions(Permissions::EditKeys);
        $key = Key::factory()->create();

        $response = $this->editKeyRequestAs($this->user, $key);

        $response->assertRedirect(route('keys.show', $key));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /** @test */
    public function can_not_delete_keys_without_the_proper_permission(): void
    {
        $this->user->givePermissionTo(Permissions::asArray());
        $this->user->revokePermissionTo(Permissions::DeleteKeys);

        $response = $this->deleteKeyRequestAs($this->user);

        $response->assertForbidden();
    }

    private function deleteKeyRequestAs(User $user): TestResponse
    {
        $host = Key::factory()->create();

        return $this->actingAs($user)
            ->delete(route('keys.destroy', $host));
    }

    /** @test */
    public function can_delete_keys_with_the_proper_permission(): void
    {
        $this->user->syncPermissions(Permissions::DeleteKeys);
        $response = $this->deleteKeyRequestAs($this->user);

        $response->assertRedirect(route('keys.index'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }
}
