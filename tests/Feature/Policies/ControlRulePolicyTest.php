<?php

namespace Tests\Feature\Policies;

use App\Enums\Permissions;
use App\Models\ControlRule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use Tests\Traits\InteractsWithPermissions;

class ControlRulePolicyTest extends TestCase
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
    public function can_not_create_rules_without_the_proper_permission(): void
    {
        $this->user->givePermissionTo(Permissions::asArray());
        $this->user->revokePermissionTo(Permissions::EditRules);

        $this->createControlRuleRequestAs($this->user)
            ->assertForbidden();
    }

    private function createControlRuleRequestAs(User $user): TestResponse
    {
        /** @var ControlRule $want */
        $want = ControlRule::factory()->make();

        return $this->actingAs($user)
            ->post(route('rules.store'), [
                'source' => $want->source_id,
                'target' => $want->target_id,
                'action' => $want->action->value,
                'name' => $want->name,
            ]);
    }

    /** @test */
    public function can_create_rules_with_the_proper_permission(): void
    {
        $this->user->syncPermissions(Permissions::EditRules);

        $this->createControlRuleRequestAs($this->user)
            ->assertRedirect(route('rules.index'))
            ->assertValid();
    }

    /** @test */
    public function can_not_delete_rules_without_the_proper_permission(): void
    {
        $this->user->givePermissionTo(Permissions::asArray());
        $this->user->revokePermissionTo(Permissions::DeleteRules);

        $this->deleteControlRulesRequestAs($this->user)
            ->assertForbidden();
    }

    private function deleteControlRulesRequestAs(User $user): TestResponse
    {
        $rule = ControlRule::factory()->create();

        return $this->actingAs($user)
            ->delete(route('rules.destroy', $rule));
    }

    /** @test */
    public function can_delete_rules_with_the_proper_permission(): void
    {
        $this->user->syncPermissions(Permissions::DeleteRules);

        $this->deleteControlRulesRequestAs($this->user)
            ->assertRedirect(route('rules.index'))
            ->assertValid();
    }
}
