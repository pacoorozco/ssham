<?php

namespace Tests\Feature\Policies;

use App\Enums\Permissions;
use App\Models\Host;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use Tests\Traits\InteractsWithPermissions;

class HostPolicyTest extends TestCase
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
    public function can_not_create_hosts_without_the_proper_permission(): void
    {
        $this->user->givePermissionTo(Permissions::asArray());
        $this->user->revokePermissionTo(Permissions::EditHosts);

        $response = $this->createHostRequestAs($this->user);

        $response->assertForbidden();
    }

    private function createHostRequestAs(User $user): TestResponse
    {
        /** @var Host $want */
        $want = Host::factory()->make();

        return $this->actingAs($user)
            ->post(route('hosts.store'), [
                'hostname' => $want->hostname,
                'username' => $want->username,
            ]);
    }

    /** @test */
    public function can_create_hosts_with_the_proper_permission(): void
    {
        $this->user->syncPermissions(Permissions::EditHosts);
        $response = $this->createHostRequestAs($this->user);

        $response->assertRedirect(route('hosts.index'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /** @test */
    public function can_not_edit_hosts_without_the_proper_permission(): void
    {
        $this->user->givePermissionTo(Permissions::asArray());
        $this->user->revokePermissionTo(Permissions::EditHosts);

        $response = $this->editHostRequestAs($this->user);

        $response->assertForbidden();
    }

    private function editHostRequestAs(User $user): TestResponse
    {
        $host = Host::factory()->create([
            'enabled' => true,
        ]);

        return $this->actingAs($user)
            ->put(route('hosts.update', $host), [
                'enabled' => false,
            ]);
    }

    /** @test */
    public function can_edit_hosts_with_the_proper_permission(): void
    {
        $this->user->syncPermissions(Permissions::EditHosts);
        $response = $this->editHostRequestAs($this->user);

        $response->assertRedirect(route('hosts.index'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /** @test */
    public function can_not_delete_hosts_without_the_proper_permission(): void
    {
        $this->user->givePermissionTo(Permissions::asArray());
        $this->user->revokePermissionTo(Permissions::DeleteHosts);

        $response = $this->deleteHostRequestAs($this->user);

        $response->assertForbidden();
    }

    private function deleteHostRequestAs(User $user): TestResponse
    {
        $host = Host::factory()->create();

        return $this->actingAs($user)
            ->delete(route('hosts.destroy', $host));
    }

    /** @test */
    public function can_delete_hosts_with_the_proper_permission(): void
    {
        $this->user->syncPermissions(Permissions::DeleteHosts);
        $response = $this->deleteHostRequestAs($this->user);

        $response->assertRedirect(route('hosts.index'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }
}
