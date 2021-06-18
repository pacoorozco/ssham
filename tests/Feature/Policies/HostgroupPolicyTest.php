<?php

namespace Tests\Feature\Policies;

use App\Enums\Permissions;
use App\Models\Hostgroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
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

        $this->enablePermissionsCheck();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function can_not_create_hostgroups_without_the_proper_permission(): void
    {
        $this->user->givePermissionTo(Permissions::asArray());
        $this->user->revokePermissionTo(Permissions::EditHosts);

        $response = $this->createHostgroupRequestAs($this->user);

        $response->assertForbidden();
    }

    private function createHostgroupRequestAs(User $user): TestResponse
    {
        /** @var Hostgroup $want */
        $want = Hostgroup::factory()->make();

        return $this->actingAs($user)
            ->post(route('hostgroups.store'), [
                'name' => $want->name,
                'description' => $want->description,
            ]);
    }

    /** @test */
    public function can_create_hostgroups_with_the_proper_permission(): void
    {
        $this->user->syncPermissions(Permissions::EditHosts);
        $response = $this->createHostgroupRequestAs($this->user);

        $response->assertRedirect(route('hostgroups.index'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /** @test */
    public function can_not_edit_hostgroups_without_the_proper_permission(): void
    {
        $this->user->givePermissionTo(Permissions::asArray());
        $this->user->revokePermissionTo(Permissions::EditHosts);
        /** @var Hostgroup $hostgroup */
        $hostgroup = Hostgroup::factory()->create();

        $response = $this->editHostgroupRequestAs($this->user, $hostgroup);

        $response->assertForbidden();
    }

    private function editHostgroupRequestAs(User $user, Hostgroup $hostgroup): TestResponse
    {
        /** @var Hostgroup $want */
        $want = Hostgroup::factory()->make();

        return $this->actingAs($user)
            ->put(route('hostgroups.update', $hostgroup), [
                'name' => $want->name,
                'description' => $want->description,
            ]);
    }

    /** @test */
    public function can_edit_hostgroups_with_the_proper_permission(): void
    {
        $this->user->syncPermissions(Permissions::EditHosts);

        /** @var Hostgroup $hostgroup */
        $hostgroup = Hostgroup::factory()->create();

        $response = $this->editHostgroupRequestAs($this->user, $hostgroup);

        $response->assertRedirect(route('hostgroups.edit', $hostgroup));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /** @test */
    public function can_not_delete_hostgroups_without_the_proper_permission(): void
    {
        $this->user->givePermissionTo(Permissions::asArray());
        $this->user->revokePermissionTo(Permissions::DeleteHosts);

        $response = $this->deleteHostgroupsRequestAs($this->user);

        $response->assertForbidden();
    }

    private function deleteHostgroupsRequestAs(User $user): TestResponse
    {
        $host = Hostgroup::factory()->create();

        return $this->actingAs($user)
            ->delete(route('hostgroups.destroy', $host));
    }

    /** @test */
    public function can_delete_hostgroups_with_the_proper_permission(): void
    {
        $this->user->syncPermissions(Permissions::DeleteHosts);
        $response = $this->deleteHostgroupsRequestAs($this->user);

        $response->assertRedirect(route('hostgroups.index'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }
}
