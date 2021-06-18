<?php

namespace Tests\Feature\Policies;

use App\Enums\Permissions;
use App\Models\Keygroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use Tests\Traits\InteractsWithPermissions;

class KeygroupPolicyTest extends TestCase
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
    public function can_not_create_keygroups_without_the_proper_permission(): void
    {
        $this->user->givePermissionTo(Permissions::asArray());
        $this->user->revokePermissionTo(Permissions::EditKeys);

        $response = $this->createKeygroupRequestAs($this->user);

        $response->assertForbidden();
    }

    private function createKeygroupRequestAs(User $user): TestResponse
    {
        /** @var Keygroup $want */
        $want = Keygroup::factory()->make();

        return $this->actingAs($user)
            ->post(route('keygroups.store'), [
                'name' => $want->name,
                'description' => $want->description,
            ]);
    }

    /** @test */
    public function can_create_keygroups_with_the_proper_permission(): void
    {
        $this->user->syncPermissions(Permissions::EditKeys);
        $response = $this->createKeygroupRequestAs($this->user);

        $response->assertRedirect(route('keygroups.index'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /** @test */
    public function can_not_edit_keygroups_without_the_proper_permission(): void
    {
        $this->user->givePermissionTo(Permissions::asArray());
        $this->user->revokePermissionTo(Permissions::EditKeys);
        /** @var Keygroup $keygroup */
        $keygroup = Keygroup::factory()->create();

        $response = $this->editKeygroupRequestAs($this->user, $keygroup);

        $response->assertForbidden();
    }

    private function editKeygroupRequestAs(User $user, Keygroup $keygroup): TestResponse
    {
        /** @var Keygroup $want */
        $want = Keygroup::factory()->make();

        return $this->actingAs($user)
            ->put(route('keygroups.update', $keygroup), [
                'name' => $want->name,
                'description' => $want->description,
            ]);
    }

    /** @test */
    public function can_edit_keygroups_with_the_proper_permission(): void
    {
        $this->user->syncPermissions(Permissions::EditKeys);

        /** @var Keygroup $keygroup */
        $keygroup = Keygroup::factory()->create();

        $response = $this->editKeygroupRequestAs($this->user, $keygroup);

        $response->assertRedirect(route('keygroups.edit', $keygroup));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /** @test */
    public function can_not_delete_keygroups_without_the_proper_permission(): void
    {
        $this->user->givePermissionTo(Permissions::asArray());
        $this->user->revokePermissionTo(Permissions::DeleteKeys);

        $response = $this->deleteKeygroupsRequestAs($this->user);

        $response->assertForbidden();
    }

    private function deleteKeygroupsRequestAs(User $user): TestResponse
    {
        $key = Keygroup::factory()->create();

        return $this->actingAs($user)
            ->delete(route('keygroups.destroy', $key));
    }

    /** @test */
    public function can_delete_keygroups_with_the_proper_permission(): void
    {
        $this->user->syncPermissions(Permissions::DeleteKeys);
        $response = $this->deleteKeygroupsRequestAs($this->user);

        $response->assertRedirect(route('keygroups.index'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }
}
