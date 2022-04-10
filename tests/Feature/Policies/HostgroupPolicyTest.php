<?php

namespace Tests\Feature\Policies;

use App\Enums\Permissions;
use App\Models\Hostgroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\InteractsWithPermissions;

class HostgroupPolicyTest extends TestCase
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
    public function can_not_create_hostgroups_without_the_proper_permission(): void
    {
        $this->user->givePermissionTo(Permissions::asArray());
        $this->user->revokePermissionTo(Permissions::EditHosts);

        /** @var Hostgroup $want */
        $want = Hostgroup::factory()->make();

        $this
            ->actingAs($this->user)
            ->post(route('hostgroups.store'), [
                'name' => $want->name,
                'description' => $want->description,
            ])
            ->assertForbidden();
    }

    /** @test */
    public function can_create_hostgroups_with_the_proper_permission(): void
    {
        $this->user->syncPermissions(Permissions::EditHosts);

        /** @var Hostgroup $want */
        $want = Hostgroup::factory()->make();

        $this
            ->actingAs($this->user)
            ->post(route('hostgroups.store'), [
                'name' => $want->name,
                'description' => $want->description,
            ])
            ->assertRedirect(route('hostgroups.index'))
            ->assertValid();
    }

    /** @test */
    public function can_not_edit_hostgroups_without_the_proper_permission(): void
    {
        $this->user->givePermissionTo(Permissions::asArray());
        $this->user->revokePermissionTo(Permissions::EditHosts);

        /** @var Hostgroup $group */
        $group = Hostgroup::factory()->create();

        /** @var Hostgroup $want */
        $want = Hostgroup::factory()->make();

        $this
            ->actingAs($this->user)
            ->put(route('hostgroups.update', $group), [
                'name' => $want->name,
                'description' => $want->description,
            ])
            ->assertForbidden();
    }

    /** @test */
    public function can_edit_hostgroups_with_the_proper_permission(): void
    {
        $this->user->syncPermissions(Permissions::EditHosts);

        /** @var Hostgroup $group */
        $group = Hostgroup::factory()->create();

        /** @var Hostgroup $want */
        $want = Hostgroup::factory()->make();

        $this
            ->actingAs($this->user)
            ->put(route('hostgroups.update', $group), [
                'name' => $want->name,
                'description' => $want->description,
            ])
            ->assertRedirect(route('hostgroups.edit', $group))
            ->assertValid();
    }

    /** @test */
    public function can_not_delete_hostgroups_without_the_proper_permission(): void
    {
        $this->user->givePermissionTo(Permissions::asArray());
        $this->user->revokePermissionTo(Permissions::DeleteHosts);

        /** @var Hostgroup $group */
        $group = Hostgroup::factory()->create();

        /** @var Hostgroup $want */
        $want = Hostgroup::factory()->make();

        $this
            ->actingAs($this->user)
            ->delete(route('hostgroups.destroy', $group))
            ->assertForbidden();
    }

    /** @test */
    public function can_delete_hostgroups_with_the_proper_permission(): void
    {
        $this->user->syncPermissions(Permissions::DeleteHosts);

        /** @var Hostgroup $group */
        $group = Hostgroup::factory()->create();

        $this
            ->actingAs($this->user)
            ->delete(route('hostgroups.destroy', $group))
            ->assertRedirect(route('hostgroups.index'))
            ->assertValid();
    }
}
