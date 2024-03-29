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

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use App\Enums\Permissions;
use App\Models\ControlRule;
use App\Models\Hostgroup;
use App\Models\Keygroup;
use App\Models\User;
use Generator;
use Tests\Feature\InteractsWithPermissions;
use Tests\Feature\TestCase;

final class ControlRuleControllerTest extends TestCase
{
    use InteractsWithPermissions;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setupRolesAndPermissions();

        $this->user = User::factory()->create();
    }

    #[Test]
    public function users_should_not_see_the_index_view(): void
    {
        $this
            ->actingAs($this->user)
            ->get(route('rules.index'))
            ->assertForbidden();
    }

    #[Test]
    public function viewers_should_see_the_index_view(): void
    {
        $this->user->givePermissionTo(Permissions::ViewRules);

        $this
            ->actingAs($this->user)
            ->get(route('rules.index'))
            ->assertSuccessful()
            ->assertViewIs('rule.index');
    }

    #[Test]
    public function users_should_not_see_the_new_rule_form(): void
    {
        Keygroup::factory()->create();

        Hostgroup::factory()->create();

        $this
            ->actingAs($this->user)
            ->get(route('rules.create'))
            ->assertForbidden();
    }

    #[Test]
    public function editors_should_see_the_new_rule_form(): void
    {
        $this->user->givePermissionTo(Permissions::EditRules);

        Keygroup::factory()
            ->count(2)
            ->create();

        Hostgroup::factory()
            ->count(2)
            ->create();

        $this
            ->actingAs($this->user)
            ->get(route('rules.create'))
            ->assertSuccessful()
            ->assertViewIs('rule.create')
            ->assertViewHas('sources')
            ->assertViewHas('targets');
    }

    #[Test]
    public function users_should_not_create_rules(): void
    {
        /** @var ControlRule $want */
        $want = ControlRule::factory()->make();

        $this
            ->actingAs($this->user)
            ->post(route('rules.store'), [
                'name' => $want->namw,
                'source' => $want->source->id,
                'target' => $want->target->id,
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing(ControlRule::class, [
            'name' => $want->name,
        ]);
    }

    #[Test]
    public function editors_should_create_rules(): void
    {
        $this->user->givePermissionTo(Permissions::EditRules);

        /** @var ControlRule $want */
        $want = ControlRule::factory()->make();

        $this
            ->actingAs($this->user)
            ->post(route('rules.store'), [
                'name' => $want->name,
                'source' => $want->source->id,
                'target' => $want->target->id,
            ])
            ->assertRedirect(route('rules.index'))
            ->assertValid();

        $this->assertDatabaseHas(ControlRule::class, [
            'name' => $want->name,
            'source_id' => $want->source->id,
            'target_id' => $want->target->id,
        ]);
    }

    #[Test]
    #[DataProvider('provideWrongDataForRuleCreation')]
    public function editors_should_get_errors_when_creating_rules_with_wrong_data(
        array $data,
        array $errors
    ): void {
        $this->user->givePermissionTo(Permissions::EditRules);

        // Rule to validate unique rules...
        ControlRule::factory()->create([
            'name' => 'foo',
        ]);

        /** @var ControlRule $want */
        $want = ControlRule::factory()->make();

        $formData = [
            'name' => $data['name'] ?? $want->name,
            'source' => $data['source'] ?? $want->source->id,
            'target' => $data['target'] ?? $want->target->id,
        ];

        $this
            ->actingAs($this->user)
            ->post(route('rules.store'), $formData)
            ->assertInvalid($errors);

        $this->assertDatabaseMissing(ControlRule::class, [
            'name' => $formData['name'],
            'source_id' => $formData['source'],
            'target_id' => $formData['target'],
        ]);
    }

    public static function provideWrongDataForRuleCreation(): Generator
    {
        yield 'name is empty' => [
            'data' => [
                'name' => '',
            ],
            'errors' => ['name'],
        ];

        yield 'source ! a keys group' => [
            'data' => [
                'source' => 100,
            ],
            'errors' => ['source'],
        ];

        yield 'target ! a hosts group' => [
            'data' => [
                'target' => 100,
            ],
            'errors' => ['target'],
        ];

        yield 'source and target is taken' => [
            'data' => [
                'source' => 1,
                'target' => 1,
            ],
            'errors' => ['source'],
        ];
    }

    #[Test]
    public function users_should_not_delete_rules(): void
    {
        $rule = ControlRule::factory()->create();

        $this
            ->actingAs($this->user)
            ->delete(route('rules.destroy', $rule))
            ->assertForbidden();
    }

    #[Test]
    public function eliminators_should_delete_rules(): void
    {
        $this->user->givePermissionTo(Permissions::DeleteRules);

        $rule = ControlRule::factory()->create();

        $this
            ->actingAs($this->user)
            ->delete(route('rules.destroy', $rule))
            ->assertRedirect(route('rules.index'));

        $this->assertModelMissing($rule);
    }
}
